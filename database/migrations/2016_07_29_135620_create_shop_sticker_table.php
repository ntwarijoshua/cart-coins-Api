<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopStickerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_sticker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('payment_type')->nullable();
            $table->integer('company_id')->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('sticker_id')->unsigned()->index();
            $table->foreign('sticker_id')->references('id')->on('stickers')->onDelete('cascade');
            $table->double('price',15,2)->nullable();
            $table->string('currency')->nullable();
            $table->boolean('completed')->default(1);
            $table->softDeletes();
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
        Schema::drop('shop_sticker');
    }
}
