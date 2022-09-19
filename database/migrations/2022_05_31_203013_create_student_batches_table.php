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
        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments', 'id')
                ->nullOnDelete();

            $table->foreignId('session_id')
                ->constrained('session_years', 'id')
                ->onDelete('cascade');
            $table->integer('current_semester')->default(1);
            $table->integer('total_semester')->default(12);
            $table->timestamp('batch_end_at')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('student_batches');
    }
};
