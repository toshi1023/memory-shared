<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_histories', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('title')->comment('タイトル');
            $table->text('content')->comment('内容');
            $table->tinyInteger('type')->comment('送信種別');
            $table->text('option')->nullable()->comment('送信対象者条件');
            $table->integer('send_count')->unsigned()->nullable()->comment('送信カウント');
            $table->dateTime('reservation_date')->comment('送信予約日時');
            $table->tinyInteger('status')->comment('送信ステータス');
            $table->text('memo')->nullable()->comment('備考');
            $table->integer('update_user_id')->unsigned()->comment('更新ユーザ');

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
        Schema::dropIfExists('push_histories');
    }
}
