<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function importUser()
    {
        try{
            //get user data
            $userRaw = $this->getUserData();
            $password = 'password';
            $status = false;
            if (User::where('email', $userRaw->email)->count() === 0) {
                $user = new User();
                $user->name = $userRaw->name;
                $user->email = $userRaw->email;
                $user->username = $userRaw->username;
                $user->password = $password;
                $status = $user->saveOrFail() ? 'User saved' : 'Failed';
            } else {
                $status = 'Email already exists';
            }
            return response()->json([
                'message'=>'ok',
                'rawUserData'=>$userRaw,
                'import_status'=>$status,
                'defaultPassword'=>$password
            ]);
        }catch (\Exception $e){
            return response()->json([
                'message'=>$e->getMessage(),
            ], 500);
        }
    }

    private function getUserData($id=NULL)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jsonplaceholder.typicode.com/users/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: jsonplaceholder.typicode.com",
                "Postman-Token: e2b3c88a-ec72-42ec-837c-e1febd2c913e,2122b1ea-b1ab-4ce3-a2f4-e2f995a4ee5d",
                "User-Agent: PostmanRuntime/7.20.1",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error #:" . $err);
        } else {
            $user_data =  json_decode($response);
            return $user_data[rand(0, count($user_data)-1)];
        }
    }
}
