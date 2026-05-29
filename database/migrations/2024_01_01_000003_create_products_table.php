<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('barcode')->nullable();
            $table->string('external_source')->nullable();
            $table->string('external_id')->nullable();
            $table->boolean('is_external')->default(false);
            $table->string('image')->nullable(); // URL ou path local
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
