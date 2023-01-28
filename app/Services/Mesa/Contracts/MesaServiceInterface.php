<?php

namespace App\Services\Mesa\Contracts;

interface MesaServiceInterface
{
    /**
     * Generate boards on guest number
     * 
     * @param  int  $eventId
     * @param  int  $howManySeats
     */
    public function boardGenerator(int $eventId, int $howManySeats);
}
