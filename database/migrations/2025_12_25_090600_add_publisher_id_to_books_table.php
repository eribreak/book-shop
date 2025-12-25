<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'publisher_id')) {
                $table->foreignId('publisher_id')
                    ->nullable()
                    ->constrained('publishers')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'publisher_id')) {
                $table->dropForeign(['publisher_id']);
                $table->dropColumn('publisher_id');
            }
        });
    }
};
