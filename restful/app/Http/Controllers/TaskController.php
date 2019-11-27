<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Task ;

class TaskController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return response()->jsonSuccess($request->user()->tasks->toArray());
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
			'name' => 'required',
			'start' => 'nullable|date',
			'due' => 'nullable|date',
			'end' => 'nullable|date',
    ]);
		
		if ($validator->fails()) 
			return response()->jsonFail($validator->errors()->all());
		
		$model = new Task;
		foreach($model->getFillable() as $field) if($request->has($field))
		{
			$value = $request->input($field);
			$model->$field = $value;
		}
		Auth::user()->tasks()->save($model);
		
		return response()->jsonSuccess($model);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
		$model = Auth::user()->tasks()->find($id);
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
		$model = Auth::user()->tasks()->find($id);
		if (!$model) 
			return response()->jsonFail('Data not found', 404);
		
		$validator = Validator::make($request->all(), [
			'name' => 'nullable',
			'start' => 'nullable|date',
			'due' => 'nullable|date',
			'end' => 'nullable|date',
    ]);
		
		if ($validator->fails()) 
			return response()->jsonFail($validator->errors()->all());
		
		foreach($model->getFillable() as $field) if($request->has($field))
		{
			$value = $request->input($field);
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
	public function destroy(Request $request, $id)
	{
		$model = Auth::user()->tasks()->find($id);
		if (!$model)  return response()->jsonFail('Data not found', 404);
		
		$model->delete();		
		return response()->jsonSuccess($model);
	}
}
