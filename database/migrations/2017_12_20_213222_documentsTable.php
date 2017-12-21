<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'files', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->string('displayName', 255);
                $table->string('filename', 255);
                $table->integer('item')->nullable();
                $table->integer('site')->index();
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
        Schema::dropIfExists('files');
    }
}
