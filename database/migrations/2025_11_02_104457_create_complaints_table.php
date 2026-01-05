<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['COMPLAINT', 'REPORT'])->default('COMPLAINT');
            $table->enum('complaint_type', [
                'ARTIST_TO_ADMIN',
                'ARTIST_TO_GESTIONNAIRE',
                'ADMIN_TO_SUPERADMIN',
                'SUPERADMIN_TO_ADMIN',
                'ADMIN_TO_GESTIONNAIRE',
                'ADMIN_TO_AGENT',
                'GESTIONNAIRE_TO_ADMIN',
                'GESTIONNAIRE_TO_AGENT',
                'AGENT_TO_ADMIN',
                'AGENT_TO_GESTIONNAIRE'
            ])->default('ARTIST_TO_ADMIN');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('super_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('gestionnaire_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->nullOnDelete();
            $table->foreignId('artist_id')->nullable()->constrained('artists')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_role', 32)->nullable();
            $table->string('target_role', 32)->nullable();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('location_link')->nullable();
            $table->json('images')->nullable();
            $table->json('files')->nullable();
            $table->foreignId('mission_id')->nullable();
            $table->text('admin_response')->nullable();
            $table->json('admin_response_images')->nullable();
            $table->text('super_admin_response')->nullable();
            $table->json('super_admin_response_images')->nullable();
            $table->text('gestionnaire_response')->nullable();
            $table->json('gestionnaire_response_images')->nullable();
            $table->text('agent_response')->nullable();
            $table->json('agent_response_images')->nullable();
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'RESOLVED'])->default('PENDING');
            $table->timestamp('responded_at')->nullable();
            $table->json('hidden_by_users')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('complaints');
    }
};
