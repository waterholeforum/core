<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('structure', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position')->default(0);
            $table->morphs('content');
            $table->boolean('is_listed')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('structure');
    }
};
