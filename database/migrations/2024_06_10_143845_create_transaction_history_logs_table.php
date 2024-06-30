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
        Schema::create('transaction_history_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->char('transaction_id')->index();
            $table->char('created_by')->index();
            $table->char('updated_by')->index()->nullable();
            $table->integer('amount_of_transaction');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_history_logs');
    }
};
