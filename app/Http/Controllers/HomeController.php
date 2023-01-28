<?php

namespace App\Http\Controllers;
use App\Services\Evento\EventoService;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $eventos;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        EventoService $eventos
    )
    {
        $this->middleware('auth');
        $this->eventos = $eventos;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $eventos = $this->eventos->getList()->toArray();

        return view('home', [
            'eventos' => $eventos
        ]);
    }
}
