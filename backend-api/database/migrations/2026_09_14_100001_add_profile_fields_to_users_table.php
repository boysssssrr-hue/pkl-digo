<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nip')) $table->string('nip')->nullable()->unique()->after('name');
            if (!Schema::hasColumn('users', 'division_id')) $table->foreignId('division_id')->nullable()->after('email')->constrained('divisions')->nullOnDelete();
            if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user')->after('password');
            if (!Schema::hasColumn('users', 'status')) $table->string('status')->default('active')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn(['nip', 'division_id', 'role', 'status']);
        });
    }
};