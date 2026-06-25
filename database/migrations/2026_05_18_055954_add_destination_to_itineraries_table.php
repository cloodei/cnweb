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
        Schema::table('itineraries', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('group_location_id')->nullable()->after('location_id')->constrained('group_locations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_location_id');
            $table->dropConstrainedForeignId('location_id');
        });
    }
};
