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
        Schema::dropIfExists('site_servico');
        Schema::create('site_orcamento_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_orcamento_id')->references('id')->on('site_orcamento');
            $table->foreignId('servico_id')->references('id')->on('servicos');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_orcamento_servico');
    }
};
