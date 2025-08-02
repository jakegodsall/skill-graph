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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();

            $table->unsignedBigInteger('linkable_id')->nullable();
            $table->string('linkable_type')->nullable();
            $table->string('link')->nullable();
            $table->boolean('dropdownOnly')->default(0);
            $table->boolean('internal')->default(1);

            $table->boolean('all_permissions')->default(1);
            $table->boolean('active')->default(1);

            $table->foreignId('parent_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->unsignedBigInteger('pos')->default(1);

            $table->enum('type', ['Admin', 'User']);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained('menu')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_permissions');
        Schema::dropIfExists('menu');
    }
};
