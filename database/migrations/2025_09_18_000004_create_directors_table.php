<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('directors');
    }
};
