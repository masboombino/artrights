<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agency_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_wallet_id')->constrained('agency_wallets')->onDelete('cascade');
            $table->foreignId('pv_id')->nullable()->constrained('pv')->nullOnDelete();
            $table->enum('direction', ['IN', 'OUT']);
            $table->decimal('amount', 14, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('agency_wallet_transactions');
    }
};

