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
        
        Schema::table('site_orcamento', function (Blueprint $table) {
            $table->decimal('velocidade_solicitada', 10)->nullable();
        });
        
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->decimal('velocidade', 10)->nullable()->change();
        });
        
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->string('nome')->change();
            $table->string('telefone')->nullable()->change();
            $table->string('cnpj')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('representante')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
