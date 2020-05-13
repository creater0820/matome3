<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sureds', function (Blueprint $table) {
            
                $table->increments('id');
                $table->integer('url_id');
                $table->string('title');
                $table->time('date');
                $table->string('publisher');
                $table->string('comment');
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
        Schema::dropIfExists('sureds');
    }
}
