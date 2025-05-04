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
            $table->nullableMorphs('actor'); // Polymorphic relation to the actor (like a user/admin)
            $table->morphs('subject'); // Polymorphic relation to the affected model
            $table->string('action')->index();
            $table->text('description')->nullable();
            // old_value
            // new_value
            // $table->string('ip_address');
            // $table->string('user_agent');
            $table->timestamps();
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
