<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patron_book', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patron_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamp('due_back')->nullable();
            $table->timestamps();
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patron_book');
    }
};
