<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_books', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('sale_id')->unsigned();
            $table->bigInteger('book_id')->unsigned();
            $table->smallInteger('amount')->unsigned();
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_books');
    }
}
