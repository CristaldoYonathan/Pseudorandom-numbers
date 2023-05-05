<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resultadochi', function (Blueprint $table) {
            $table->id();
            $table->string('metodo');
            $table->longText('fo');
            $table->float('fe');
            $table->float('chi_cuadrado_calculado');
            $table->boolean('resultado');
            $table->timestamps();
        });

        //relacion con la tabla numeros fibonacci
        Schema::table('resultadochi', function (Blueprint $table) {
//            $table->foreign('id_f')->references('id')->on('numerosfibonacci');
//            $table->foreign('id_c')->references('id')->on('numeroscongruencia');
            $table->foreignId('id_f')->nullable()->constrained('numerosfibonacci');
            $table->foreignId('id_c')->nullable()->constrained('numeroscongruencia');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultadochi');
    }
};
