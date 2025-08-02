<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Estado;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->char('uf', 2);
            $table->string('nome',191);
        });

        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->integer('cidade_id')->unsigned();
            $table->foreignIdFor(Estado::class)->constrained();
            $table->string('nome',191);
        });


        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->required();
            $table->string('telefone')->required();
            $table->string('cnpj')->unique()->required();
            $table->string('email')->unique();
            $table->string('representante');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('fornecedor_cidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cidade_id');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreignId('fornecedor_id')->references('id')->on('fornecedores');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
