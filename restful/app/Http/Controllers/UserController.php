<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->jsonSuccess(User::get()->toArray());
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => 'required|alpha_dash|min:4|unique:users,username',
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:4',
			'confirm_password' => 'required|min:4|same:password',
			'add_geo_lat' => 'nullable|numeric',
			'add_geo_lng' => 'nullable|numeric',
    ]);
		
		if ($validator->fails()) 
			return response()->jsonFail($validator->errors()->all());
		
		$model = new User;
		foreach($model->getFillable() as $field) if($request->has($field))
		{
			$value = $request->input($field);
			if($field == 'password') $value = Hash::make($value);
			$model->$field = $value;
		}
		$model->save();
		
		return response()->jsonSuccess($model);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$model = User::find($id);
		if(!$model) return response()->jsonFail('Data not found', 404); 
		else return response()->jsonSuccess($model);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$model = User::find($id);
		if (!$model) 
			return response()->jsonFail('Data not found', 404);
		
		$validator = Validator::make($request->all(), [
			'username' => 'nullable|alpha_dash|min:4|unique:users,username,'.$id,
			'name' => 'nullable',
			'email' => 'nullable|email',
			'password' => 'nullable|min:4',
			'confirm_password' => 'nullable|min:4|same:password',
			'add_geo_lat' => 'nullable|numeric',
			'add_geo_lng' => 'nullable|numeric',

    ]);
		
		if ($validator->fails()) 
			return response()->jsonFail($validator->errors()->all());
		
		foreach($model->getFillable() as $field) if($request->has($field))
		{
			$value = $request->input($field);
			if($field == 'password')
			{
				if(!$value) continue;
				$value = Hash::make($value);
			}
			$model->$field = $value;
		}
		$model->save();
		
		return response()->jsonSuccess($model);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$model = User::find($id);
		if (!$model)  return response()->jsonFail('Data not found', 404);
		
		$model->delete();		
		return response()->jsonSuccess($model);
	}

	
	public function import()
	{		
		$conn = curl_init();
		$args = array(
			CURLOPT_URL => 'https://jsonplaceholder.typicode.com/users',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 5,
		);
		curl_setopt_array($conn, $args);
		$data = json_decode(curl_exec($conn));
		$random = $data[array_rand($data, 1)];
		
		$model = new User;
		$model->name = $random->name;
		$model->email = $random->email;
		$model->password = Hash::make($random->username);
		$model->username = $random->username;

		$model->phone = $random->phone;
		$model->website = $random->website;

		$model->addr_street = $random->address->street;
		$model->addr_suite = $random->address->suite;
		$model->add_city = $random->address->city;
		$model->add_zip = $random->address->zipcode;
		$model->add_geo_lat = $random->address->geo->lat;
		$model->add_geo_lng = $random->address->geo->lng;

		$model->cpn_name = $random->company->name;
		$model->cpn_phrase = $random->company->catchPhrase;
		$model->cpn_bs = $random->company->bs;

		$model->save();
		
		return response()->jsonSuccess($model);
	}
}
