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
            $table->integer('master_id');
            $table->integer('level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_questions', function (Blueprint $table) {
            $table->dropColumn('master_id');
            $table->dropColumn('level_id');
        });
    }
};
