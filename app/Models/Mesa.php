<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string nome_mesa
 * @property int qtd_lugares
 * @property int num_mesa
 * @property int id_evento
 */
class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesa';

    protected $fillable = [
        'id',
        'nome_mesa',
        'qtd_lugares',
        'num_mesa',
        'id_evento',
    ];

    protected $attributes = [
        'nome_mesa' => null,
        'qtd_lugares' => null,
        'num_mesa' => null,
        'id_evento' => null,
    ];
}
