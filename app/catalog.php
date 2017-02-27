<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Events\SaveImage;

class catalog extends Model
{
    protected $guarded = ['id'];
    protected $table = 'catalog';
    public $timestamps = FALSE;

}
