<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnitSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unitsekolah', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_unit',30);
            $table->timestamps();
        });

        Schema::table('data_alumni', function (Blueprint $table) {
            $table->foreign('id_unit')->references('id')->on('unitsekolah')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
