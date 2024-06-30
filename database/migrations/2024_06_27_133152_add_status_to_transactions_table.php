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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->after('description');
        });
        Schema::table('transaction_history_logs', function (Blueprint $table) {
            $table->string('status')->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->after('description');
        });
        Schema::table('transaction_history_logs', function (Blueprint $table) {
            $table->string('status')->after('note');
        });
    }
};
