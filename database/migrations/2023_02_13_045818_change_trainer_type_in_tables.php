<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTrainerTypeInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_management_meeting', function (Blueprint $table) {
            $table->integer('trainer_id')->nullable()->after('trainer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_management_meeting', function (Blueprint $table) {
            $table->dropColumn('trainer_id');
        });
    }
}
