<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string email
 * @property int id_mesa
 */
class Convidado extends Model
{
    use HasFactory;

    protected $table = 'convidado';

    protected $fillable = [
        'email',
        'id_mesa',
        'numero_da_cadeira',
        'nome_convidado',
        'id_convidado_acompanhante',
        'company_name',
    ];

    protected $attributes = [
        'email' => null,
        'id_mesa' => null,
        'id_convidado_acompanhante' => null,
    ];
}
