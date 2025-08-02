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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->string('titulo')->nullable()->default(null)->after('id');
        });
        
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('site_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->references('id')->on('sites');
            $table->foreignId('servico_id')->references('id')->on('servicos');
            $table->timestamps();
        });

        Schema::create('cotacao_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotacao_id')->references('id')->on('cotacoes');
            $table->foreignId('servico_id')->references('id')->on('servicos');
            $table->decimal('velocidade', 10)->nullable()->default(null);
            $table->decimal('adesao', 12, 2)->nullable()->default(null);
            $table->decimal('mensalidade', 12, 2)->nullable()->default(null);
            $table->string('obs')->nullable()->default(null);
            $table->timestamps();
        });
        
        Schema::table('cotacoes', function(Blueprint $table) {
            $table->renameColumn('velocidade', 'vel_down');
            $table->string('vel_down')->nullable()->change();
            $table->string('vel_up')->nullable();
        });
        Schema::table('site_orcamento', function(Blueprint $table) {
            $table->renameColumn('velocidade_solicitada', 'vel_solicitada_down');
            $table->string('vel_solicitada_down')->nullable()->change();
            $table->string('vel_solicitada_up')->nullable();
        });
        Schema::table('cotacao_servico', function(Blueprint $table) {
            $table->renameColumn('velocidade', 'vel_down');
            $table->string('vel_down')->nullable()->change();
            $table->string('vel_up')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
