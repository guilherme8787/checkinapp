<?php

namespace App\Repository\Mesa\Contracts;

interface MesaRepositoryInterface
{
    /**
     * @param  array  $data
     */
    public function create(array $data);

    /**
     * @param  int  $eventId
     */
    public function getNextBoardNumber(int $eventId);
}
