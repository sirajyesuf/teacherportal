<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tls', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->timestamp('date')->nullable();
            $table->string('program')->nullable();
            $table->string('music_day')->nullable();
            $table->string('music_prog')->nullable();
            $table->string('duration')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('tls');
    }
}
