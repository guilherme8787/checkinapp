<?php

namespace App\Repository\Convidado;

use App\Models\Convidado;
use App\Repository\Convidado\Contracts\ConvidadoRepositoryInterface;

class ConvidadoRepository implements ConvidadoRepositoryInterface
{
    /**
     * @var Convidado
     */
    private $model;

    /**
     * ConvidadoRepository constructor
     */
    public function __construct(Convidado $model)
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
}
