<?php

namespace App\Http\Controllers;

use App\Models\Convidado;
use App\Models\Mesa;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConvidadoController extends Controller
{
    public function create(Request $request)
    {
        $convidado = new Convidado();

        $mesa = Mesa::where('id', $request->id_mesa)->first();
        $idEvento = $mesa->id_evento;

        $convidadosDaMesa = Convidado::where('id_mesa', $mesa->id)->count();

        $mesaCheia = $mesa->qtd_lugares - $convidadosDaMesa < 1;

        if ($mesaCheia) {
            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'error' => 'Mesa cheia'
            ]);
        }


        $ultimaCadeiraLivre = $convidado->where('id_mesa', $request->id_mesa)->max('numero_da_cadeira');

        if ($ultimaCadeiraLivre == null || $ultimaCadeiraLivre == 0) {
            $proximaCadeiraLivre = 1;
        } else {
            $proximaCadeiraLivre = $ultimaCadeiraLivre + 1;
        }

        $email = $request->email;
        $idMesa = $request->id_mesa;
        $nome = $request->nome_convidado;
        $companyName = $request->company_name;

        //Try catch para verificar se o convidado foi cadastrado com sucesso
        try {
            $convidado->email = $email;
            $convidado->id_mesa = $idMesa;
            $convidado->nome_convidado = $nome;
            $convidado->numero_da_cadeira = $proximaCadeiraLivre;
            $convidado->company_name = $companyName;
            $convidado->save();

            $mesa = new Mesa();
            $mesa = $mesa->where('id', $idMesa)->get();
            $idEvento = $mesa->toArray()[0]['id_evento'];

            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Função para excluir um convidado
    public function delete($id)
    {
        $convidado = Convidado::find($id);

        //Try catch para verificar se o convidado foi excluído com sucesso
        try {
            $convidado->delete();
            return response()->json($convidado, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Função para atualizar o convidado
    public function update(Request $request, $id): JsonResponse
    {
        $convidado = Convidado::find($id);

        $email = $request->email;
        $idMesa = $request->id_mesa;

        //Try catch para verificar se o convidado foi atualizado com sucesso
        try {
            $convidado->email = $email;
            $convidado->id_mesa = $idMesa;
            $convidado->save();

            return response()->json($convidado, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Função para retornar todos os convidados de uma mesa
     *
     * @param int $idMesa
     */
    public function list(int $idMesa)
    {
        $convidados = Convidado::where('id_mesa', $idMesa);
        $convidados = $convidados->get();
        $informacoes = array();

        $convidadosunicos = array_column($convidados->toArray(), 'id');

        foreach ($convidadosunicos as $index => $convidado) {

            $informacao['id_convidado'] = $convidados[$index]->id;
            $informacao['email'] = $convidados[$index]->email;
            $informacao['id_mesa'] = $convidados[$index]->id_mesa;

            $informacoes[] = $informacao;
        }

        return view(
            'convidado.index',
            [
                'data' => $informacoes,
                'idMesa' => $idMesa
            ]
        );
    }

    /**
     * @param  Request  $request
     * @param  int  $idEvento
     */
    public function mudarConvidadoMesa(Request $request, int $idEvento): RedirectResponse
    {
        $idConvidado = $request->idConvidado;
        $numeroDaMesa = $request->numero_da_mesa;

        try {
            $mesa = new Mesa();

            $idMesa = $mesa->where('num_mesa', $numeroDaMesa)->get();

            if ($idMesa->isEmpty()) {
                throw new Exception('Mesa não encontrada.');
            }

            $idMesa = $idMesa->toArray()[0]['id'];

            $convidado = Convidado::find($idConvidado);

            $convidado->id_mesa = $idMesa;
            $convidado->save();

            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Função que altera a cadeira do convidado
    public function mudarConvidadoCadeira(Request $request, int $idEvento): RedirectResponse
    {
        $idConvidado = $request->idConvidado;
        $numeroDaCadeira = $request->numero_da_cadeira;

        try {
            $convidado = Convidado::find($idConvidado);

            $convidado->numero_da_cadeira = $numeroDaCadeira;
            $convidado->save();

            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param  Request  $request
     */
    public function adicionarAcompanhante(Request $request)
    {
        try {
            $acompanhante = new Convidado();

            $convidado = Convidado::first()->where('id', $request->id_convidado)->get();

            $acompanhante->email = $request->email;
            $acompanhante->nome_convidado = $request->nome_convidado;
            $acompanhante->id_mesa = $request->id_mesa;
            $acompanhante->company_name = $request->company_name;
            $acompanhante->numero_da_cadeira = $convidado->toArray()[0]['numero_da_cadeira'] . $this->proximaLetraAcompanhante($request->id_convidado);
            $acompanhante->id_convidado_acompanhante = $request->id_convidado;

            $acompanhante->save();

            return redirect()->route('get-mesas', [
                'idEvento' => $request->id_evento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('get-mesas', [
                'idEvento' => $request->id_evento,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param  int  $idConvidado
     */
    private function proximaLetraAcompanhante(int $idConvidado)
    {
        $convidado = Convidado::where('id_convidado_acompanhante', $idConvidado)->get();

        if (is_null($convidado)) {
            return 'A';
        }

        $alternativas = range('B', 'Z');

        return $alternativas[$convidado->count()];
    }

    /**
     * Função para listar os convidados por mesa
     *
     * @param  int  $idEvento
     */
    public function listGuests(int $idEvento)
    {
        $convidados = Convidado::where('mesa.id_evento', $idEvento)
                    ->select('convidado.id as id_convidado', 'convidado.nome_convidado', 'convidado.email', 'convidado.company_name', 'convidado.numero_da_cadeira', 'convidado.id_mesa', 'convidado.id_convidado_acompanhante', 'mesa.num_mesa')
                    ->join('mesa', 'convidado.id_mesa', '=', 'mesa.id')
                    ->join('eventos', 'mesa.id_evento', '=', 'eventos.id')
                    ->get();

        $convidados = $convidados->toArray();

        return view(
            'convidado.edit',
            [
                'data' => $convidados,
                'idEvento' => $idEvento
            ]
        );
    }

    /**
     * Método para atualizar a mesa via Ajax ou Fetch
     *
     * @param  Request  $request
     * @param  int  $idConvidado
     * @return JsonResponse
     */
    public function updateAjaxConvidado(Request $request, int $idConvidado): JsonResponse
    {
        $convidado = Convidado::where('id', $idConvidado)->first();

        $nomeConvidado = $request->nome_convidado;
        $email = $request->email;
        $companyName = $request->company_name;
        $numCadeira = $request->numero_da_cadeira;

        //Try catch para verificar se a mesa foi atualizada com sucesso
        try {
            $convidado->nome_convidado = $nomeConvidado;
            $convidado->email = $email;
            $convidado->company_name = $companyName;
            $convidado->numero_da_cadeira = $numCadeira;
            $convidado->save();

            return response()->json([
                'data' => $convidado,
                'idConvidado' => $idConvidado,
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    public function updateAllConvidado(Request $request, int $idMesa)
    {
        $data = $request->all();

        //Try catch para verificar se o convidado foi atualizado com sucesso
        try {
            foreach ($data['id'] as $idConvidado) {

                $convidado = Convidado::where('id', $idConvidado)->update([
                    'nome_convidado' => $data['nome_convidado' . $idConvidado],
                    'email' => $data['email' . $idConvidado],
                    'company_name' => $data['company_name' . $idConvidado],
                    'numero_da_cadeira' => $data['numero_da_cadeira' . $idConvidado]
                ]);
            }

            return redirect()->route('list-guests', [
                'idMesa' => $idMesa,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('list-guests', [
                'idMesa' => $idMesa,
                'error' => $e->getMessage()
            ]);
        }
    }
}
