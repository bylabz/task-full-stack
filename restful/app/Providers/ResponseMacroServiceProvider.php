<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
	/**
	 * Register the application's response macros.
	 *
	 * @return void
	 */
	public function boot()
	{
		Response::macro('jsonFail', function ($errors, $errorCode=400) {
			return Response::make( json_encode(['errors'=>$errors]), $errorCode, ['content-type'=>'application/json']);
		});
		
		Response::macro('jsonSuccess', function ($data=null) {
			return Response::make(json_encode(['success'=>true, 'data'=>$data]), 200, ['content-type'=>'application/json']);
		});
	}
}