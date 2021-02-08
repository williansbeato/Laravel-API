<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientePet extends Migration
{
   
    public function up()
    {
        Schema::create('cliente_pets', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('pet_id');

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('pet_id')->references('id')->on('pets');

            $table->primary(['cliente_id','pet_id']);

            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('cliente_pets');
    }
}
