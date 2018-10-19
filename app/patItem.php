<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class patItem extends Model
{
    protected $guarded = ['id'];
    protected $table = 'pat_items';
    public $timestamps = FALSE;
}
