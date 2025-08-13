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
        Schema::table('site_orcamento', function(Blueprint $table) {            
            $table->dropForeign(['site_orcamento_id']);

            $table->unsignedBigInteger('site_orcamento_id')->nullable()->default(null)->change();

            $table->foreign('site_orcamento_id')->references('id')->on('site_orcamento');
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
