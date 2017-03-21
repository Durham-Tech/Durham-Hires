<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'bookings';
}
