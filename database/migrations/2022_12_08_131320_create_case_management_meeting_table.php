<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseManagementMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_management_meeting', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->date('date')->nullable();
            $table->string('trainer')->nullable();
            $table->string('package')->nullable();
            $table->integer('num')->nullable();
            $table->text('description')->nullable();
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_management_meeting');
    }
}
