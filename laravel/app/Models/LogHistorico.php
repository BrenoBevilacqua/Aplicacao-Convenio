<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogHistorico extends Model
{
    protected $casts = [
        'data_modificacao' => 'datetime',
    ];
    
    protected $table = 'log_historico'; // Nome correto da tabela no banco

    protected $fillable = [
        'user_id',
        'acao',
        'numero_convenio',
        'ano_convenio',
        'data_modificacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}