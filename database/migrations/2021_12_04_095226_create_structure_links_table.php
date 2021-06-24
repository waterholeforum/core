<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('structure_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('href');
            $table->string('icon')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('structure_links');
    }
};
