<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pv_artwork', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pv_id')->constrained('pv')->onDelete('cascade');
            $table->foreignId('artwork_id')->constrained('artworks')->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('set null');
            $table->decimal('hours_used', 8, 2)->default(1);
            $table->unsignedInteger('plays_count')->default(1);
            $table->decimal('base_rate', 10, 2)->default(200);
            $table->decimal('fine_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pv_artwork');
    }
};

