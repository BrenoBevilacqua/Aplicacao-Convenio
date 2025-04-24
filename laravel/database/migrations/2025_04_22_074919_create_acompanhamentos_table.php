<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('acompanhamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['em_execucao', 'finalizado', 'cancelado']);
            $table->boolean('monitorado')->default(false);
            $table->timestamps(); // cria created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('acompanhamentos');
    }
    };
