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
        Schema::create('course_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses', 'id')
                ->onDelete('cascade');
            $table->foreignId('co_id')
                ->constrained('course_outcomes', 'id')
                ->onDelete('cascade');
            $table->foreignId('po_id')
                ->nullable()
                ->constrained('program_outcomes', 'id')
                ->onDelete('nullable');
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
        Schema::dropIfExists('course_assigns');
    }
};
