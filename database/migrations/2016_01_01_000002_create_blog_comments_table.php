<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use Kurt\Modules\Core\Traits\GetUserModelData;

class CreateBlogCommentsTable extends Migration
{
    use GetUserModelData;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {

            $table->increments('id');

            $table->boolean('approved');
            
            $table->text('content');

            $userModel = $this->getUserModel();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references($userModel->getKeyName())
                ->on($userModel->getTable())
                ->onDelete('cascade');

            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('blog_posts')
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
        Schema::drop('blog_comments');
    }
}
