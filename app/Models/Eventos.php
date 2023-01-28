<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_guest_list',
        'id_visitor_list',
        'event_description',
        'event_img',
        'color',
        'font_color',
        'event_description',
        'credential_expiration_date',
        'url_inscricao',
        'event_bg_image',
        'teclado',
    ];

    protected $attributes = [
        'event_img' => null,
        'color' => null,
        'font_color' => null,
        'event_description' => null,
        'credential_expiration_date' => null,
    ];

    public function getGuestListId(): int
    {
        return $this->id_guest_list;
    }
}
