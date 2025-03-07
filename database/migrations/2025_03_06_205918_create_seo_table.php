<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('author')->nullable(); // Added author field
            $table->string('robots')->nullable(); // Added robots field
            $table->string('follow_type')->nullable();
            $table->json('sociale')->nullable();
            $table->json('params')->nullable();

            // For RalphJSmit/Laravel-SEO package
            $table->morphs('model'); // This creates model_id and model_type columns

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo');
    }
};
