<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'files';
    public $timestamps = false;
}
