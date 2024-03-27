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
        Schema::create('appointment_teacher_students', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();
            $table->string('type_lesson');
            $table->text('reason')->nullable();

            $table->bigInteger('appointment_available_id')->unsigned();
            $table->foreign('appointment_available_id')->references('id')->on('appointment_availables')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_teacher_students');
    }
};
