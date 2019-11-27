<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'username', 
		'phone', 'website', 
		'addr_street', 'addr_suite', 'add_city', 'add_zip', 'add_geo_lat', 'add_geo_lng', 
		'cpn_name', 'cpn_phrase', 'cpn_bs', 'api_token',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'api_token', 'created_at', 'updated_at'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	
	
	public function tasks()
	{
		return $this->hasMany('App\Task');
	}
}
