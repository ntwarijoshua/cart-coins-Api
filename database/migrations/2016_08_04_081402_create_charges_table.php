<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount',15,2);
            $table->string('currency');
            $table->string('customer')->nullable();
            $table->string('source');
            $table->string('description')->nullable();
            $table->binary('metadata')->nullable();
            $table->boolean('capture')->nullable();
            $table->string('statement_description')->nullable();
            $table->string('receipt_email')->nullable();
            $table->double('application_fee',15,2)->nullable();
            $table->binary('shipping')->nullable();
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
        Schema::drop('charges');
    }
}
