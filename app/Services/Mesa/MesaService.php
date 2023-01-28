<?php

namespace App\Services\Mesa;

use App\Services\Mesa\Contracts\MesaServiceInterface;
use App\Services\SharpSpring\SharpSpringService;
use App\Repository\Convidado\Contracts\ConvidadoRepositoryInterface;
use App\Repository\Mesa\Contracts\MesaRepositoryInterface;
use App\Repository\Evento\EventoRepositoryInterface;

class MesaService implements MesaServiceInterface
{
    /**
     * @var ConvidadoRepositoryInterface
     */
    private $convidadoRepository;

    /**
     * @var MesaRepositoryInterface
     */
    private $mesaRepository;

    /**
     * @var EventoRepositoryInterface
     */
    private $eventoRepository;

    /**
     * @var SharpSpringService
     */
    private $sharpService;

    /**
     * @var array
     */
    private $availableBoardsInSs = [];

    /**
     * @var array
     */
    private $guests = [];

    /**
     * MesaService constructor
     *
     * @param  ConvidadoRepositoryInterface  $convidadoRepository
     * @param  EventoRepositoryInterface  $eventoRepository
     * @param  MesaRepositoryInterface  $mesaRepository
     * @param  SharpSpringService  $sharpService
     */
    public function __construct(
        ConvidadoRepositoryInterface $convidadoRepository,
        EventoRepositoryInterface $eventoRepository,
        MesaRepositoryInterface $mesaRepository,
        SharpSpringService $sharpService
    ) {
        $this->convidadoRepository = $convidadoRepository;
        $this->eventoRepository = $eventoRepository;
        $this->mesaRepository = $mesaRepository;
        $this->sharpService = $sharpService;
    }

    /**
     * @inheritDoc
     */
    public function boardGenerator(int $eventId, int $howManySeats)
    {
        $event = $this->eventoRepository->get($eventId);

        $guests = $this->sharpService->getAllMembersOfList($event->getGuestListId());

        $countOfGuests = count($guests);

        $numberOfBoardsToGen = intval(ceil($countOfGuests / $howManySeats));

        for ($i = 0; $i < $numberOfBoardsToGen; $i++) {
            $nextBoardNumber = $this->mesaRepository->getNextBoardNumber($eventId);

            if (blank($nextBoardNumber)) {
                $nextBoardNumber = 0;
            }

            $mesas[] = $this->mesaRepository->create([
                'nome_mesa' => 'Mesa ' . $nextBoardNumber + 1,
                'qtd_lugares' => $howManySeats,
                'num_mesa' => $nextBoardNumber + 1,
                'id_evento' => $eventId,
            ]);
        }

        if ($this->checkPanicControlAndFieldsOfMesaInSs()) {
            $indexFieldNameMesa = config('paniccontrol.mesa_sharp_field.field_name_name');

            $this->availableBoardsInSs = array_values(
                array_filter(
                    array_column(
                        $guests,
                        $indexFieldNameMesa
                    )
                )
            );

            $mesasJaRenomeadas = [];

            // tem um bug aqui, são 240 mesas ele faz map apenas de 229 ?
            foreach ($mesas as $mesa) {
                $boardName = $this->nextBoardName();

                if (! blank($boardName)) {
                    if (! in_array($boardName, $mesasJaRenomeadas)) {
                        $mesa->nome_mesa = $boardName;
                        $mesa->save();
                    }
                }

                $mesasJaRenomeadas[] = $boardName;
            }

            foreach ($mesas as $mesa) {
                $map[$mesa->nome_mesa] = array_map(function ($value) use ($mesa, $indexFieldNameMesa) {
                    if ($mesa->nome_mesa === trim($value[$indexFieldNameMesa])) {
                        return $value;
                    }
                }, $guests);
            }

            foreach ($map as $index => $maped) {
                $map[$index] = array_filter($maped);
            }

            foreach ($map as $maped) {
                if (!empty($maped)) {
                    foreach ($maped as $index => $val) {
                        unset($guests[$index]);
                    }
                }
            }
        }

        if (!isset($map)) {
            foreach ($mesas as $mesa) {
                $map[$mesa->nome_mesa] = [];
            }
        }

        $this->guests = array_values($guests);

        foreach ($map as $index => $maped) {
            $guestFlag = true;
            while ($guestFlag) {
                if (count($map[$index]) >= $howManySeats) {
                    $guestFlag = false;
                } else {
                    $nextGuest = $this->nextGuest();
                    if (!is_null($nextGuest)) {
                        $map[$index][] = $nextGuest;
                    } else {
                        $guestFlag = false;
                    }
                }
            }
        }


        foreach ($map as $index => $maped) {
            $mesa = $this->searchMesa($mesas, trim($index));

            if (!blank($mesa)) {
                $cont = 1;

                foreach ($maped as $convidado) {

                    $this->convidadoRepository->create([
                        'email' => blank($convidado['emailAddress']) ? 'Não informado' : $convidado['emailAddress'],
                        'id_mesa' => $mesa->id,
                        'numero_da_cadeira' => $cont,
                        'nome_convidado' => $convidado['firstName'] . ' ' . $convidado['lastName'],
                        'id_convidado_acompanhante' => null,
                        'company_name' => $convidado['companyName'],
                    ]);

                    $cont++;
                }
            }
        }
    }

    private function searchMesa(array $mesas, string $nameNeedle)
    {
        foreach ($mesas as $mesa) {
            if ($nameNeedle === $mesa->nome_mesa) {
                return $mesa;
            }
        }

        return null;
    }

    /**
     * Verify if paninc control is enable and have a name of column of ss in config
     *
     * @return bool
     */
    private function checkPanicControlAndFieldsOfMesaInSs(): bool
    {
        if (!config('paniccontrol.mesa_sharp_field.panic')) {
            return false;
        }

        if (blank(config('paniccontrol.mesa_sharp_field.field_name_name'))) {
            return false;
        }

        return true;
    }

    /**
     * Return next board name based in return of Ss
     */
    private function nextBoardName(): ?string
    {
        if (!empty($this->availableBoardsInSs)) {
            $nextName = $this->availableBoardsInSs[0];
            unset($this->availableBoardsInSs[0]);
            $this->availableBoardsInSs = array_values($this->availableBoardsInSs);
        }

        return trim($nextName) ?? null;
    }

    /**
     * Return next guest
     */
    private function nextGuest(): ?array
    {
        if (!empty($this->guests)) {
            $nextGuest = $this->guests[0];
            unset($this->guests[0]);
            $this->guests = array_values($this->guests);
        }

        return $nextGuest ?? null;
    }
}
