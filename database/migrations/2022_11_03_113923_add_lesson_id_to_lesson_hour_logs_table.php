<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonIdToLessonHourLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_hour_logs', function (Blueprint $table) {
            $table->integer('lesson_id')->nullable()->after('student_id');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_hour_logs', function (Blueprint $table) {
            $table->dropColumn('lesson_id');
        });
    }
}
