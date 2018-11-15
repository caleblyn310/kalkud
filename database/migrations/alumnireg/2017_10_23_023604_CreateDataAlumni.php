<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataAlumni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('data_alumni', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama',50);
            $table->string('alamat',255);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin',['L','P']);
            $table->string('telepon',15)->unique();
            $table->string('email',50)->unique();
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
        Schema::dropIfExists('data_alumni');
    }
}
