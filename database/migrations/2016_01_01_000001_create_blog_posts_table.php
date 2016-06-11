<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {

            $table->increments('id');

            $table->string('title');
            $table->string('slug')->index();

            $table->integer('type')->index();

            $table->text('content');

            $userModel = app(config('kurt_modules.user_model'));

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references($userModel->getKeyName())
                ->on($userModel->getTable())
                ->onDelete('cascade');

            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('blog_categories')
                ->onDelete('cascade');

            $table->timestamp('published_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blog_posts');
    }
}
