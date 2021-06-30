<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('category_id');
            $table->boolean('deleted')->default(0);

            $table->foreign('article_id')
            ->references('id')->on('articles')
            ->onUpdate('cascade');

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onUpdate('cascade');

            $table->unique(['category_id', 'article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_categories');
    }
}
