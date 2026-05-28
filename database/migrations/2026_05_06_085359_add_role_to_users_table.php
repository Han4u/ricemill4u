<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('petani')->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('petani')->after('email');
            });
        }
    }

    public function down(): void
    {
        // Don't drop the column if it was originally defined in the main users table migration
    }
};