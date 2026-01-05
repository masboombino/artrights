<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('pv', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('mission_id')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_type')->nullable();
            $table->dateTime('date_of_inspection')->nullable();
            $table->enum('status', ['OPEN', 'PENDING', 'CLOSED'])->default('OPEN');
            $table->enum('payment_method', ['CASH', 'CHEQUE'])->nullable();
            $table->enum('payment_status', ['PENDING', 'VALIDATED', 'INVALID'])->default('PENDING');
            $table->boolean('agent_payment_confirmed')->default(false);
            $table->timestamp('agent_confirmed_at')->nullable();
            $table->mediumText('file_path')->nullable();
            $table->decimal('base_rate', 10, 2)->default(200);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('cash_received_amount', 12, 2)->default(0);
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('funds_released_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pv');
    }
};
