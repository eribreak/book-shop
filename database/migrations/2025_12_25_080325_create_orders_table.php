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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_code', 20);
            $table->string('full_name', 100);
            $table->string('email', 100);
            $table->string('mobile', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            // 0: borrowing, 1: returned, 2: overdue, 3: lost
            $table->timestamps();
            $table->softDeletes();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
