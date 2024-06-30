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

        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->char('class_id')->index();
            $table->char('user_id')->index()->nullable();
            $table->char('created_by')->index();
            $table->char('updated_by')->index()->nullable();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
