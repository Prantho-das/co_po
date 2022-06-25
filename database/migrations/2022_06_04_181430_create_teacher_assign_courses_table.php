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
        Schema::create('teacher_assign_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses', 'id')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignId('semester_id')
                ->constrained('semesters', 'id')
                ->onDelete('cascade');
            $table->foreignId('batch_id')
                ->constrained('student_batches', 'id')
                ->onDelete('cascade');
            $table->foreignId('session_id')
                ->constrained('session_years', 'id')
                ->onDelete('cascade');
            $table->timestamp('course_complete_at')->nullable();
            $table->tinyInteger('exam_assign_status')->default(0);
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
        Schema::dropIfExists('teacher_assign_courses');
    }
};
