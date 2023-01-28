<?php

namespace App\Repository\Mesa;

use App\Models\Mesa;
use App\Repository\Mesa\Contracts\MesaRepositoryInterface;

class MesaRepository implements MesaRepositoryInterface
{
    /**
     * @var Mesa
     */
    private $model;

    /**
     * MesaRepository constructor
     */
    public function __construct(Mesa $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function getNextBoardNumber(int $eventId)
    {
        return $this->model
            ->where('id_evento', $eventId)
            ->max('num_mesa');
    }
}
