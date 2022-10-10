<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            $table->json('data')->nullable();
            $table->unsignedInteger('width')->default(0)->index();
            $table->unsignedInteger('height')->default(0);
            $table->text('path')->nullable();
            $table->foreignId('site_id')->constrained('sites');
            $table->foreignId('client_id')->constrained('clients');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
};
