<?php

namespace App\Http\Controllers;

use App\Models\Convidado;

class RelatoriosController extends Controller
{
    /**
     * @param  int  $idEvento
     */
    public function index(int $idEvento)
    {
        $csv = Convidado::select('email', 'num_mesa', 'nome_mesa', 'id_evento', 'event_description')
            ->join('mesa', 'mesa.id', '=', 'convidado.id_mesa')
            ->join('eventos', 'eventos.id', '=', 'mesa.id_evento')
            ->where('id_evento', '=', $idEvento)
            ->get();

        header('Content-type: application/csv');
        header("Content-Disposition: attachment; filename=relatorio.csv");

        echo 'EMAIL DO CONVIDADO;NUMERO DA MESA;NOME DA MESA;ID DO EVENTO;NOME DO EVENTO' . PHP_EOL;
        foreach($csv as $row) {
            echo $row->email . ';' . $row->num_mesa . ';' . $row->nome_mesa . ';' . $row->id_evento . ';' . $row->event_description .  PHP_EOL;
        }
    }
}
