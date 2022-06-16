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
        Schema::create('exam_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copo_id')
                ->constrained('course_assigns', 'id')
                ->onDelete('cascade');
            $table->foreignId('co_id')
                ->constrained('course_outcomes', 'id')
                ->onDelete('cascade');
            $table->foreignId('po_id')
                ->constrained('program_outcomes', 'id')
                ->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses', 'id')
                ->onDelete('cascade');
            $table->foreignId('t_assign_courses_id')
                ->constrained('teacher_assign_courses', 'id')
                ->onDelete('cascade');
            $table->integer('teacher_id');
            $table->string('name');
            $table->string('marks');
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
        Schema::dropIfExists('exam_assigns');
    }
};
