<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->string('stage_name')->nullable();
            $table->foreignId('wallet_id')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('identity_document')->nullable();
            $table->enum('status', ['PENDING_VALIDATION', 'APPROVED', 'REJECTED'])->default('PENDING_VALIDATION');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('artists');
    }
};
