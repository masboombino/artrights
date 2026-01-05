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
        Schema::table('missions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['gestionnaire_id']);
            $table->dropForeign(['agent_id']);
            
            // Make columns nullable
            $table->foreignId('gestionnaire_id')->nullable()->change();
            $table->foreignId('agent_id')->nullable()->change();
            
            // Re-add foreign key constraints with null handling
            $table->foreign('gestionnaire_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['gestionnaire_id']);
            $table->dropForeign(['agent_id']);
            
            // Make columns NOT NULL again
            $table->foreignId('gestionnaire_id')->nullable(false)->change();
            $table->foreignId('agent_id')->nullable(false)->change();
            
            // Re-add foreign key constraints
            $table->foreign('gestionnaire_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }
};
