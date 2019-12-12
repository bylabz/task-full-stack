<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'name','description','price','user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function isItMine()
    {
        return $this->attributes['user_id'] == Auth::id();
    }
}
