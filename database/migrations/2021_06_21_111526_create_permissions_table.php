<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('scope');
            $table->morphs('recipient');
            $table->string('ability');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
