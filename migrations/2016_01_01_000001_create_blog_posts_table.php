<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            
            $table->text('content');

            $table->integer('user_id')->unsigned()->nullable();

            $userModel = app(config('auth.providers.users.model'));

            $table->foreign('user_id')
                ->references($userModel->getKeyName())
                ->on($userModel->getTable())
                ->onDelete('cascade');

            $table->integer('category_id')->unsigned()->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

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
