<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reaction_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_default_posts')->default(0);
            $table->boolean('is_default_comments')->default(0);
            $table->boolean('allow_multiple')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reaction_sets');
    }
};
