<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('structure_headings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('structure_headings');
    }
};
