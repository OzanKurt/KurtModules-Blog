<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {

            $table->increments('id');
            
            $table->text('content');

            $table->integer('user_id')->unsigned();
            $table->integer('post_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $userModel = app(config('auth.providers.users.model'));

            $table->foreign('user_id')
                ->references($userModel->getKeyName())
                ->on($userModel->getTable())
                ->onDelete('cascade');

            $table->foreign('post_id')
                ->references('id')
                ->on('blog_posts')
                ->onDelete('cascade');
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
