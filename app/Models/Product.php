<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use App\Traits\UuidsTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UuidsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;
}