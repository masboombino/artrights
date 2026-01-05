<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pv_id')->nullable()->constrained('pv')->onDelete('cascade');
            $table->foreignId('device_type_id')->nullable()->constrained('device_types')->onDelete('set null');
            $table->string('name');
            $table->string('type')->nullable();
            $table->decimal('coefficient', 6, 2)->default(1);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('devices');
    }
};
