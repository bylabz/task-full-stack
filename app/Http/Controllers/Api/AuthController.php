<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $data = Validator::make($request->json()->all(), [
                'username'=>['required', 'unique:users,username'],
                'email'=>['required', 'unique:users,email', 'email'],
                'password'=>['required','min:8'],
                'name'=>['required'],
            ])->validate();
            if (!($user = User::create($data))) {
                throw new \Exception('Error creating user');
            }
            return Response::json([
                'message'=>'User created'
            ]);
        }catch (ValidationException $e) {
            return Response::json([
                'message'=>'validation error',
                'validation_errors'=>$e->errors()
            ], 400);
        }
        catch (\Exception $e){
            return Response::json([
                'message'=>$e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $data = Validator::make($request->json()->all(), [
                'email'=>['required', 'email'],
                'password'=>['required'],
            ])->validate();
            $user = User::where('email', $data['email'])->first();
            if (!$user)
                throw new \Exception('User not found');
            if (!$user->passwordIsValid($data['password'])) {
                throw new \Exception('Wrong Password');
            }
            $user->update(['api_token'=>Str::random(80)]);
            return Response::json([
                'message'=>'Login Success',
                'user'=>$user->refresh()
            ]);
        }catch (ValidationException $e) {
            return Response::json([
                'message'=>'validation error',
                'validation_errors'=>$e->errors()
            ], 400);
        }
        catch (\Exception $e){
            return Response::json([
                'message'=>$e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {

    }
}
