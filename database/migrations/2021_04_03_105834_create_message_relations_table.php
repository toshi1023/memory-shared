<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_relations', function (Blueprint $table) {
            $table->integer('user_id1')->unsigned()->comment('ユーザID1');
            $table->integer('user_id2')->unsigned()->comment('ユーザID2');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id2')->references('id')->on('users')->onDelete('cascade');

            // プライマリキー設定
            $table->unique(['user_id1', 'user_id2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_relations');
    }
}
