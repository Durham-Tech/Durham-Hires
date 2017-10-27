<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPdfOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'sites', function (Blueprint $table) {
                //
                $table->string('logo', 255)->nullable();
                $table->string('address', 1024)->nullable();
                $table->string('invoicePrefix', 255)->nullable();
                $table->string('managerTitle', 255)->nullable();
                $table->string('dueTime', 255)->nullable();
                $table->string('vatName', 255)->nullable();
                $table->string('vatNumber', 255)->nullable();
                $table->string('sortCode', 255)->nullable();
                $table->string('accountNumber', 255)->nullable();
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
        Schema::table(
            'sites', function (Blueprint $table) {
                //
            }
        );
    }
}
