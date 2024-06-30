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

        Schema::create('arrears', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->char('student_id')->index();
            $table->integer('amount_of_arrears');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrears');
    }
};
