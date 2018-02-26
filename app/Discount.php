<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'discount_codes';
    public $timestamps = false;
}
