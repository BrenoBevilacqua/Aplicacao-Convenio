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
        Schema::table('acompanhamentos', function (Blueprint $table) {
            $table->integer('porcentagem_conclusao')->default(0)->after('monitorado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhamentos', function (Blueprint $table) {
            $table->dropColumn('porcentagem_conclusao');
        });
    }
};