<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create(
            'admins', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->string('user', 20);
                $table->string('email', 255);
                $table->tinyInteger('privileges');
                $table->integer('site');
            }
        );
        Schema::create(
            'booked_items', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bookingID')->index();
                $table->integer('item');
                $table->integer('number');
            }
        );
        Schema::create(
            'bookings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->string('user', 255)->nullable();
                $table->string('email', 255);
                // $table->string('email', 255)->unique();
                $table->integer('isDurham');
                $table->dateTime('start');
                $table->dateTime('end');
                $table->integer('days');
                $table->boolean('internal')->default(0);
                $table->boolean('template')->default(0);
                $table->integer('status');
                $table->boolean('vat')->default(0);
                $table->float('totalPrice')->nullable();
                $table->string('invoice', 255)->nullable();
                $table->integer('discDays')->default(0);
                $table->integer('discType')->default(0);
                $table->float('discValue')->default(0);
                $table->string('fineDesc')->nullable();
                $table->float('fineValue')->default(0);
                $table->integer('site');
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            }
        );
        Schema::create(
            'catalog', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description', 255);
                $table->text('details')->nullable();
                $table->string('image', 255)->nullable();
                $table->integer('quantity');
                $table->integer('category');
                $table->float('dayPrice');
                $table->float('weekPrice');
                $table->integer('orderOf')->default(999);
                $table->integer('site');
            }
        );
        Schema::create(
            'categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('subCatOf')->nullable();
                $table->integer('orderOf')->default(999);
                $table->integer('site');
            }
        );
        Schema::create(
            'content', function (Blueprint $table) {
                $table->increments('id');
                $table->string('page', 255);
                // $table->string('page', 255)->unique();
                $table->string('name', 255);
                $table->text('content');
                $table->integer('site');
            }
        );
        Schema::create(
            'custom_items', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('booking')->index();
                $table->string('description', 255);
                $table->integer('number');
                $table->float('price');
            }
        );
        Schema::create(
            'sites', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->string('slug', 255);
                $table->boolean('deleted')->default(0);
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
        //
    }
}
