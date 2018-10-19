<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class patRecord extends Model
{
    protected $guarded = ['id'];
    protected $table = 'pat_records';
    public $timestamps = TRUE;
}
