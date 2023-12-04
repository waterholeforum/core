<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reaction_types', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('reaction_set_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->tinyInteger('score');
            $table->unsignedSmallInteger('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reaction_types');
    }
};
