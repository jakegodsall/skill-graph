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
        Schema::create('activity_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete(); // The activity that depends on something
            $table->foreignId('depends_on_activity_id')->constrained('activities')->cascadeOnDelete(); // The activity it depends on
            $table->timestamps();
            
            // Prevent duplicate dependencies
            $table->unique(['activity_id', 'depends_on_activity_id']);
            
            // Indexes for better performance
            $table->index('activity_id');
            $table->index('depends_on_activity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_dependencies');
    }
};
