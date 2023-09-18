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
        Schema::table('sub_questions', function (Blueprint $table) {
           $table->dropForeign(['next_question_id']);
           $table->dropColumn('next_question_id');

           $table->dropForeign(['main_question_id']);
            $table->dropColumn('main_question_id');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('next_question_id');
            $table->foreign('next_question_id')->references('id')->on('master_questions');
            $table->unsignedBigInteger('main_question_id');
            $table->foreign('main_question_id')->references('id')->on('master_questions');
             
        });
    }
};
