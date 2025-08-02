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
            $table->tinyInteger('barra')->nullable()->default(null);
        });

        Schema::table('cotacao_servico', function (Blueprint $table) {
            $table->tinyInteger('barra')->nullable()->default(null);
        });

        Schema::table('cotacoes', function (Blueprint $table) {
            $table->tinyInteger('barra')->nullable()->default(null);
            $table->decimal('custo_fixo', 12, 2)->nullable();
        });
        
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->decimal('gear_noc', 12, 2)->default(null)->nullable();
            $table->smallInteger('custo_fixo_percent')->default(null)->nullable();
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
