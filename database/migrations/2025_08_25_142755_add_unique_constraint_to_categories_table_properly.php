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
        Schema::table('categories', function (Blueprint $table) {
            try {
                // Try to add the unique constraint - if it already exists, the exception will be caught
                $table->unique(['user_id', 'name', 'type'], 'categories_user_name_type_unique');
            } catch (\Exception $e) {
                // Constraint already exists or other error - continue silently
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            try {
                $table->dropUnique('categories_user_name_type_unique');
            } catch (\Exception $e) {
                // Constraint doesn't exist or other error - continue silently
            }
        });
    }
};
