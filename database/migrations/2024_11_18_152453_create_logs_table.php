<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); 
            $table->text('description')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address')->nullable(); 
            $table->string('model_type')->nullable(); 
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
