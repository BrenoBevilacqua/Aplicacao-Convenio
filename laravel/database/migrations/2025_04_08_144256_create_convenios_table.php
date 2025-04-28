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
        Schema::create('convenios', function (Blueprint $table) {
            $table->id(); // Código (ID)
            $table->integer('numero_convenio'); // Número do convênio
            $table->year('ano_convenio'); // Ano do convênio
            $table->string('identificacao'); // Identificação do convênio
            $table->text('objeto'); // Objeto

            // Recursos
            $table->string('fonte_recursos'); // Fonte de recursos
            $table->string('concedente'); // Concedente
            $table->decimal('valor_repasse', 15, 2); // Valor do repasse
            $table->decimal('valor_contrapartida', 15, 2); // Valor da contrapartida
            $table->decimal('valor_total', 15, 2); // Valor total (repasse + contrapartida)
            $table->string('parlamentar')->nullable(); // Parlamentar (opcional)
            $table->string('conta_vinculada')->nullable(); // Conta vinculada (opcional)

            // Status
            $table->string('natureza_despesa'); // Natureza de despesa
            $table->date('data_assinatura'); // Data da assinatura
            $table->date('data_vigencia'); // Data de vigência
            $table->timestamps(); // created_at e updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
