<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name');
            $table->string('wilaya');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
        
        // Add foreign key constraint for users.agency_id after agencies table is created
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
        
        // Add foreign key constraint for artists.agency_id after agencies table is created
        Schema::table('artists', function (Blueprint $table) {
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
    }

    public function down(): void {
        // Drop foreign key constraints first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
        });
        
        Schema::table('artists', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
        });
        
        Schema::dropIfExists('agencies');
    }
};
