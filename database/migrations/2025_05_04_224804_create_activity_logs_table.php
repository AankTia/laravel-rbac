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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('action')->index();
            $table->text('description')->nullable();
            $table->nullableMorphs('subject'); // Polymorphic relation to the affected model / which model was changed
            // $table->string('ip_address');
            // $table->string('user_agent');
            $table->json('properties')->nullable(); // Properties: holds the old and new attributes

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
