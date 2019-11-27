<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	
	protected $table = 'tasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name','start','due','end','description',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
}
