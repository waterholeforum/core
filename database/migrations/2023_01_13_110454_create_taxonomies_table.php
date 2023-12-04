<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('allow_multiple')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('taxonomies');
    }
};
