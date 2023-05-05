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
        Schema::create('numerosfibonacci', function (Blueprint $table) {
            $table->id();

            $table->string('valoresBaseF');
            $table->longText('valoresGeneradosF');

            $table->timestamps();
        });

        //relacion con la tabla usuarios
        Schema::table('numerosfibonacci', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numerosfibonacci');
    }
};
