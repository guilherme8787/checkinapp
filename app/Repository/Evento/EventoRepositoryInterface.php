<?php

namespace App\Repository\Evento;

use Illuminate\Database\Eloquent\Model;

interface EventoRepositoryInterface
{
    public function __construct(Model $model);
    public function getList();

    /**
     * @param  int  $id
     * @return Model|null
     */
    public function get(int $id): ?Model;

    public function store(array $data);
    public function delete(int $id);
    public function getByGuest(int $guestId);
    public function update(array $data, int $id);
    public function getByGuestList(int $id);
}
