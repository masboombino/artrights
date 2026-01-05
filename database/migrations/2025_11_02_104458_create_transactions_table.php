<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pv_id')->nullable()->constrained('pv')->onDelete('set null');
            $table->foreignId('artist_id')->nullable()->constrained('artists')->onDelete('set null');
            $table->foreignId('artwork_id')->nullable()->constrained('artworks')->onDelete('set null');
            $table->enum('type', ['PV_PAYMENT', 'WALLET_RECHARGE', 'PLATFORM_TAX', 'OTHER'])->default('PV_PAYMENT');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('payment_method', ['CASH', 'CHEQUE', 'WALLET_RECHARGE'])->default('CASH');
            $table->enum('payment_status', ['PENDING', 'VALIDATED', 'INVALID'])->default('PENDING');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
