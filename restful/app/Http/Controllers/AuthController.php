<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\User;

class AuthController extends Controller
{

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => 'required|alpha_dash|min:4',
			'password' => 'required|min:4',
    ]);
		
		if ($validator->fails()) 
			return response()->jsonFail($validator->errors()->all());
		
		$credentials = $request->only('username', 'password');
		
		if (Auth::attempt($credentials)) 
		{
			$token = hash('sha256', openssl_random_pseudo_bytes(80));
			$request->user()->forceFill(['api_token' => $token])->save();
			return response()->jsonSuccess(['token' => $token]);
		} 
		else return response()->jsonFail('Invalid username or password');
	}
	
	public function credential(Request $request)
	{
		$user = User::where('api_token',$request->input('token'))->first();
		if(!$user) 
			return response()->jsonFail('Invalid token');
		else
			return response()->jsonSuccess($user->toArray());
	}

	public function logout(Request $request)
	{
		$request->user()->forceFill(['api_token' => null])->save();
		return response()->jsonSuccess();	
	}
}
