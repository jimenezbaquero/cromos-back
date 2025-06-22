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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('total_cards')->default(100);
            $table->string('description')->nullable();
            $table->string('url_cover_photo')->nullable();
            $table->string('url_backcover_photo')->nullable();
            $table->unsignedSmallInteger('year');
            $table->foreignId('publisher_id')->constrained('publishers');
            $table->timestamps();
            
            $table->unique(['name', 'year', 'publisher_id']);
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
