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
        Schema::create('assign_marks', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('batch_id');
            $table->string('roll');
            $table->integer('course_id');
            $table->integer('copo_id');
            $table->integer('co_id');
            $table->integer('po_id');
            $table->foreignId('t_assign_courses_id')
                ->nullable()
                ->constrained('teacher_assign_courses', 'id')
                ->nullOnDelete();
            $table->integer('teacher_id');
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
        Schema::dropIfExists('assign_marks');
    }
};
