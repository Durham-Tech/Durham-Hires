<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteStyle extends Migration
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
                $table->string('accent', 10);
                $table->string('accentDark', 10);
                $table->string('accentLight', 10);
                $table->string('accentText', 10);
                $table->string('accentTextDark', 10);
                $table->string('styleSheet', 255);
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
