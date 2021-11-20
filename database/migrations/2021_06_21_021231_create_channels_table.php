<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->json('sorts')->nullable();
            $table->string('default_layout')->nullable();
            $table->boolean('sandbox')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('channels');
    }
};
