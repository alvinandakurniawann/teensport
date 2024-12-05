<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['pull_day', 'push_day', 'leg_day']);
            $table->foreignId('user_id')->constrained();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};