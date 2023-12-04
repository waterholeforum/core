<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_public')->default(0);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
