<?php

namespace App\Services\Evento;

use App\Repository\Evento\EventoRepositoryInterface;

class EventoService
{
    protected $repository;

    public function __construct(EventoRepositoryInterface $repository)
    {
        return $this->repository = $repository;
    }

    public function new(array $data)
    {
        return $this->repository->store($data);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update(array $data, int $id)
    {
        return $this->repository->update($data, $id);
    }

    public function get(int $id)
    {
        return $this->repository->get($id);
    }

    public function getByGuestList(int $id)
    {
        return $this->repository->getByGuestList($id);
    }

    public function getList()
    {
        return $this->repository->getList();
    }

    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }

}
