<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config');
    }
};
