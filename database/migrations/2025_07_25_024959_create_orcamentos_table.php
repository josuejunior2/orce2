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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cidade_id')->references('id')->on('cidades');
            $table->string('nome');
            $table->string('endereco')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cotacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->references('id')->on('fornecedores');
            $table->string('velocidade');
            $table->tinyInteger('tecnologia')->nullable()->comment('1 - fibra, 2 - rádio');
            $table->foreignId('site_id')->references('id')->on('sites');
            $table->tinyInteger('status')->default(1)->nullable()->comment('1 - Em aberto, 2 - Fechado');
            $table->string('prazo_instalacao_fornecedor')->nullable();
            $table->decimal('custo_operacional', 12, 2)->nullable();
            $table->decimal('custo_ativacao', 12, 2)->nullable();
            // Daqui pra baixo só o administrador preenche
            $table->decimal('mensal_imp', 12, 2)->comment('quanto que vai cobrar mensal com imposto')->nullable();
            $table->decimal('adesao_fornecedor', 12, 2)->nullable();
            $table->decimal('imposto_mensal', 12, 2)->nullable();
            $table->decimal('imposto_adesao', 12, 2)->nullable();
            $table->decimal('lucro_adesao', 12, 2)->nullable();
            $table->decimal('lucro_liquido', 12, 2)->nullable();
            $table->decimal('mensal_fornecedor', 12, 2)->comment('quando que o forn cobra mensal')->nullable();
            $table->decimal('custo_instalacao_imp', 12, 2)->comment('custo de instalação com imposto')->nullable();
            $table->string('prazo_instalacao')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->references('id')->on('clientes');
            $table->unsignedTinyInteger('tempo_contrato')->nullable();
            $table->string('tipo_link')->comment('se é l2l ou ld')->nullable();
            $table->tinyInteger('imposto')->nullable();
            $table->tinyInteger('status')->default(1)->nullable()->comment('1 - Em cotação, 2 - Enviado, 3 - Aprovado, 4 - Sem viabilidade');
            $table->tinyInteger('quantidade_sites')->nullable();
            $table->decimal('lucro_mensal_total', 12, 2)->nullable();
            $table->decimal('adesao_total', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('site_orcamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->references('id')->on('sites');
            $table->foreignId('orcamento_id')->references('id')->on('orcamentos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_orcamento');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('cotacoes');
        Schema::dropIfExists('orcamentos');
    }
};
