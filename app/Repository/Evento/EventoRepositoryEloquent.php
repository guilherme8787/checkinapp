<?php

namespace App\Repository\Evento;

use Illuminate\Database\Eloquent\Model;

class EventoRepositoryEloquent implements EventoRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        return $this->model = $model;
    }

    public function getList()
    {
        return $this->model->all();
    }

    /**
     * @inheritDoc
     */
    public function get(int $id): ?Model
    {
        return $this->model->where(['id' => $id])->first();
    }

    public function getByGuest(int $guestId)
    {
        return $this->model->where(['id_guest_list' => $guestId])->get();
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, int $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where(['id' => $id])->delete();
    }

    public function getByGuestList(int $id)
    {
        return $this->model->where(['id_guest_list' => $id])->get();
    }
}
