<?php

namespace App\Http\Controllers;

use App\Models\Convidado;
use App\Models\Mesa;
use App\Models\Eventos;
use Illuminate\Http\Request;

class InformacaoController extends Controller
{
    // Função que retorna um vetor com as informaçoes da mesa
    public function index(Request $request, $idEvento)
    {
        $mesas = Mesa::where('id_evento', $idEvento)->get();

        $informacoes = array();

        $mesasunicas = array_column($mesas->toArray(), 'id');

        foreach ($mesasunicas as $index => $mesa) {
            $convidados = Convidado::where('id_mesa', $mesa)->get();
            $convidados = array_column($convidados->toArray(), 'email');
            $informacao['nome_mesa'] = $mesas[$index]->nome_mesa;
            $informacao['qtd_lugares'] = $mesas[$index]->qtd_lugares;
            $informacao['num_mesa'] = $mesas[$index]->num_mesa;
            $informacao['id_evento'] = $mesas[$index]->id_evento;
            $informacao['id_mesa'] = $mesas[$index]->id;
            $informacao['convidado'] = $convidados;
            $informacoes[] = $informacao;
        }

        usort($informacoes, function ($a, $b) {
            return $a['nome_mesa'] <=> $b['nome_mesa'];
        });

        return response()->json($informacoes, 200);
    }
}
