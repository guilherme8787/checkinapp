<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatisticas extends Model
{
    use HasFactory;

    protected $fillable = [
        'emailAddress',
        'job_title',
        'state',
        'city',
        'country',
        'event_number',
    ];
}
