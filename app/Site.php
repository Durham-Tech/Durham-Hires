<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'sites';
    public $timestamps = false;
}
