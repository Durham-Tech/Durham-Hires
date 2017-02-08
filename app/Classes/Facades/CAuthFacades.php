<?php
namespace app\Classes\Facades;
use Illuminate\Support\Facades\Facade;

class CAuthFacades extends Facade{
   protected static function getFacadeAccessor() { return 'cauth'; }
}
?>