<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    protected $guarded = ['id'];
    public $timestamps = FALSE;
}
