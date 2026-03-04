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
        Schema::table('artists', function (Blueprint $table) {
            $table->string('bank_account_number')->nullable()->after('identity_document');
            $table->string('bank_account_proof')->nullable()->after('bank_account_number');
            $table->string('full_name_on_account')->nullable()->after('bank_account_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(['bank_account_number', 'bank_account_proof', 'full_name_on_account']);
        });
    }
};
