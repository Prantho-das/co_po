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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('batch_id');
            $table->string('roll');
            $table->integer('copo_id');
            $table->integer('course_id');
            $table->integer('co_id');
            $table->integer('po_id');
            $table->foreignId('t_assign_courses_id')->nullable()->constrained('teacher_assign_courses', 'id')->nullOnDelete();
            $table->integer('teacher_id');
            $table->integer('exam_id');
            $table->string('marks');
            $table->string('p_marks');
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
        Schema::dropIfExists('marks');
    }
};
