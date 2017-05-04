<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class content extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'content';
    public $timestamps = false;
}
