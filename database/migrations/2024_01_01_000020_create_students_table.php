<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('roll_no')->unique();
            $table->string('email')->nullable();
            $table->string('course');
            $table->string('department');
            $table->unsignedTinyInteger('year');
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->enum('status', ['pending', 'active', 'graduated', 'dropout'])->default('pending');
            $table->year('admission_year');
            $table->year('passout_year')->nullable();
            $table->string('gender')->nullable();
            $table->string('state_of_origin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
