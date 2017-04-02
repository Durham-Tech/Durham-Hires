<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class booked_items extends Model
{
    protected $guarded = ['id'];
    protected $table = 'booked_items';
    public $timestamps = false;
}
