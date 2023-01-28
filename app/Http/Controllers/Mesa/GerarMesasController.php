<?php

namespace App\Http\Controllers\Mesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Mesa\Contracts\MesaServiceInterface;

class GerarMesasController extends Controller
{
    /**
     * @var GerarMesasController
     */
    private $mesaService;

    /**
     * GerarMesasController contructor
     *
     * @param  MesaServiceInterface  $mesaService
     */
    public function __construct(MesaServiceInterface $mesaService)
    {
        $this->mesaService = $mesaService;
    }

    /**
     * @param  Request  $request
     * @param  int  $eventId
     */
    public function __invoke(Request $request, int $eventId)
    {
        $qntdDeLugares = $request->numeroCadeira;

        try {
            $this->mesaService->boardGenerator($eventId, $qntdDeLugares);

            return redirect()->route('get-mesas', [
                'idEvento' => $eventId,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('get-mesas', [
                'idEvento' => $eventId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
