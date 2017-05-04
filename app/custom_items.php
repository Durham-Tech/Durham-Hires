<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class custom_items extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'custom_items';
    public $timestamps = false;
}
