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
        Schema::create('resultadopoker', function (Blueprint $table) {

            $table->id();
            $table->string('metodo');
            $table->longText('fo');
            $table->longText('fe');
            $table->float('chi_cuadrado_calculado');
            $table->float('chi_cuadrado_limite');
            $table->integer('grados_de_libertad');
            $table->boolean('resultado');
            $table->timestamps();

        });

        //relacion con la tabla numeros fibonacci
        Schema::table('resultadopoker', function (Blueprint $table) {
            $table->foreignId('id_f')->nullable()->constrained('numerosfibonacci');
            $table->foreignId('id_c')->nullable()->constrained('numeroscongruencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultadopoker');
    }
};
