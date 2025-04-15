<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Acao;

class Convenio extends Model
{
    protected $fillable = [
        'numero_convenio',
        'ano_convenio',
        'identificacao',
        'objeto',
        'fonte_recursos',
        'valor_repasse',
        'valor_contrapartida',
        'valor_total',
        'concedente',
        'parlamentar',
        'conta_vinculada',
        'natureza_despesa',
        'data_assinatura',
        'data_vigencia',
    ];
    public function acoes()
    {
    return $this->hasMany(Acao::class);
    }
}
