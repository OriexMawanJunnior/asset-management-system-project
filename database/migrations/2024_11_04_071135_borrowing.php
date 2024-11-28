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
        Schema::create('borrowings', function(Blueprint $table){
            $table->id();
            $table->date('date_of_receipt');
            $table->date('date_of_return')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'late']);
            $table->foreignId('asset_id')->constrained('assets');
            $table->foreignId('employee_id')->constrained('employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing');
    }
};
