<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pat_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site');
            $table->string('patID');
            $table->string('description');
            $table->integer('type')->default(0);
            $table->integer('fuse')->nullable();
            $table->integer('itemID')->nullable();
            $table->dateTime('last_test')->nullable();
            // $table->index(['site', 'patID'], 'patItems');
        });
        Schema::create('pat_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patID');
            // $table->string('patID')->index();
            $table->dateTime('date');
            $table->float('test_current')->nullable();
            $table->float('insulation_resistance')->nullable();
            $table->float('earth_resistance')->nullable();
            $table->float('touch_current')->nullable();
            $table->float('load_current')->nullable();
            $table->float('load_power')->nullable();
            $table->float('leakage_current')->nullable();
            $table->boolean('pass')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pat_items');
        Schema::dropIfExists('pat_records');
    }
}
