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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->foreignId('collection_id')->references('id')->on('collections');
            $table->foreignId('card_type_id')->references('id')->on('card_types');
            $table->string('url_photo');
            $table->timestamps();
            
            $table->unique(['number', 'collection_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
