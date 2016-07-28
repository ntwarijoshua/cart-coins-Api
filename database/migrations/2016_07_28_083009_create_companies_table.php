<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('vat')->unique();
            $table->string('pobox')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone');
            $table->string('website')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('manager_id')->unsigned()->index()->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->integer('category_id')->nullable();
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
        Schema::drop('companies');
    }
}
