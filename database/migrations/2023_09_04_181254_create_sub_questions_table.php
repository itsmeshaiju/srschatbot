<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('next_question_id');
            $table->foreign('next_question_id')->references('id')->on('master_questions');
            $table->longText('question');
            $table->longText('answer');
            $table->boolean('status')->default(1);
            $table->boolean('is_repeat')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_questions');
    }
};
