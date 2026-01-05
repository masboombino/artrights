<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location_text')->nullable();
            $table->string('map_link')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('status', ['ASSIGNED', 'IN_PROGRESS', 'DONE', 'CANCELLED'])->default('ASSIGNED');
            $table->timestamps();
        });
        
        // Add foreign key constraints for mission_id in complaints and pv tables
        // This must be done after missions table is created
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign('mission_id')->references('id')->on('missions')->nullOnDelete();
        });
        
        Schema::table('pv', function (Blueprint $table) {
            $table->foreign('mission_id')->references('id')->on('missions')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['mission_id']);
        });
        Schema::table('pv', function (Blueprint $table) {
            $table->dropForeign(['mission_id']);
        });
        Schema::dropIfExists('missions');
    }
};

