<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscountCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'discountCodes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 255);
                $table->string('name', 255);
                $table->integer('type')->default(0);
                $table->float('value')->default(0);
                $table->integer('site')->index();
            }
        );
        Schema::table(
            'bookings', function (Blueprint $table) {
                $table->string('discName')->default('');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discountCodes');
    }
}
