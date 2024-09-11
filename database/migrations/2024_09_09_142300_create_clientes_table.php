<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->date('fecha_cita');
            $table->time('hora_cita');  
            $table->string('nombre_medico', 255); 
            $table->string('nombre_centro', 255); 
            $table->string('telefono', 9);
            $table->string('estado', 10)->default('PENDIENTE'); 
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
        Schema::dropIfExists('clientes');
    }
};
