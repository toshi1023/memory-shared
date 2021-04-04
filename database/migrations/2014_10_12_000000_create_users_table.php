<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('name')->comment('ユーザ名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->comment('パスワード');
            $table->rememberToken()->nullable()->comment('パスワード保存用トークン');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('ステータス');
            $table->string('user_agent')->nullable()->comment('ユーザエージェント');
            $table->string('image_file')->nullable()->comment('画像名');
            $table->text('memo')->nullable()->comment('備考');
            $table->integer('update_user_id')->unsigned()->nullable()->comment('更新ユーザ');
            
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
        Schema::dropIfExists('users');
    }
}
