<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_marks', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->foreignId('t_assign_courses_id')->constrained('teacher_assign_courses', 'id')
                ->onDelete('cascade');
            $table->integer('exam_id');
            $table->double('mark')->nullable();
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
        Schema::dropIfExists('draft_marks');
    }
};
