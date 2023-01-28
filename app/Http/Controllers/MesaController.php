<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\Convidado;
use App\Models\Dashboards;
use App\Models\Mesa;
use App\Services\SharpSpring\SharpSpringService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MesaController extends Controller
{
    /**
     * @var SharpSpringService
     */
    private $sharpSpringService;

    public $proximaCadeira = 1;

    /**
     * Class constructor
     */
    public function __construct(SharpSpringService $sharpSpringService)
    {
        $this->sharpSpringService = $sharpSpringService;
    }

    /**
     * @param  int  $idEvento
     */
    public function create(Request $request, int $idEvento)
    {
        $mesa = new Mesa();

        $ultimoNumeroLivre = $mesa->where('id_evento', $idEvento)->max('num_mesa');

        if ($ultimoNumeroLivre == null || $ultimoNumeroLivre == 0) {
            $proximoNumeroLivre = 1;
        } else {
            $proximoNumeroLivre = $ultimoNumeroLivre + 1;
        }

        $nomeMesa = $request->nome_mesa;
        $qtdLugar = $request->qtd_lugares;
        $numMesa = $proximoNumeroLivre;
        $idEvento = $idEvento;

        //Se o número da mesa estiver vazio, o número da mesa será igual ao número de mesas cadastradas + 1
        if ($numMesa == null) {
            $numMesa = Mesa::count() + 1;
        }

        // Verifica se o nome da mesa já existe e se existir retorna um erro
        if (Mesa::where('nome_mesa', $nomeMesa)->where('id_evento', $idEvento)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Já existe uma mesa com esse nome']);
        }

       //Try catch para verificar se a mesa foi cadastrada com sucesso
       try {
           $mesa->nome_mesa = $nomeMesa;
           $mesa->qtd_lugares = $qtdLugar;
           $mesa->num_mesa = $numMesa;
           $mesa->id_evento = $idEvento;
           $mesa->save();

           return redirect()->route('get-mesas', [
               'idEvento' => $idEvento,
               'sucesso' => true
           ]);
       } catch (Exception $e) {
           return response()->json($e->getMessage());
       }
    }

    // Função para excluir a mesa
    public function delete(int $idMesa)
    {
        $mesa = Mesa::where('id', $idMesa)->first();

        $idEvento = $mesa->id_evento;

        //Try catch para verificar se a mesa foi excluída com sucesso
        try {
            $mesa->delete();
            return redirect()->route('get-mesas', [
                'idEvento' => $idEvento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Função para atualizar a mesa
    public function update(Request $request, int $idMesa, Exception $e)
    {
        $mesa = Mesa::where('id', $idMesa)->first();

        $idEvento = $mesa->id_evento;

        $nomeMesa = $request->nome_mesa;
        $qtdLugar = $request->qtd_lugares;
        $numMesa = $request->num_mesa;

        if (Mesa::where('id', '!=', $idMesa)->where('nome_mesa', $nomeMesa)->where('id_evento', $idEvento)->exists()) {
            // Verifica se o nome da mesa já existe e se existir retorna um erro
            if (Mesa::where('nome_mesa', $nomeMesa)->where('id_evento', $idEvento)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Já existe uma mesa com esse nome']);
            }
            // Verifica se o número da mesa já existe e se existir retorna um erro
            elseif (Mesa::where('num_mesa', $numMesa)->where('id_evento', $idEvento)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Já existe uma mesa com esse número']);
            }
        }

        //Try catch para verificar se a mesa foi atualizada com sucesso
        try {
            $mesa->nome_mesa = $nomeMesa;
            $mesa->qtd_lugares = $qtdLugar;
            $mesa->num_mesa = $numMesa;
            $mesa->save();

            return redirect()->route('get-mesa', [
                'data' => $mesa,
                'idMesa' => $idMesa,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Função para atualizar a mesa
    public function updateAll(Request $request, int $idEvento)
    {
        $data = $request->all();

        //Try catch para verificar se a mesa foi atualizada com sucesso
        try {
            foreach ($data['id'] as $idMesa) {
                $mesa = Mesa::where('id', $idMesa)->update([
                    'nome_mesa' => $data['nome_mesa' . $idMesa],
                    'qtd_lugares' => $data['qtd_lugares' . $idMesa],
                    'num_mesa' => $data['num_mesa' . $idMesa],
                    'ativo' => $data['ativo' . $idMesa]
                ]);
            }

            return redirect()->route('list-mesas', [
                'idEvento' => $idEvento,
                'sucesso' => true
            ]);
        } catch (Exception $e) {
            return redirect()->route('list-mesas', [
                'idEvento' => $idEvento,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Método para atualizar a mesa via Ajax ou Fetch
     *
     * @param  Request  $request
     * @param  int  $idMesa
     * @return JsonResponse
     */
    public function updateAjax(Request $request, int $idMesa): JsonResponse
    {
        $mesa = Mesa::where('id', $idMesa)->first();

        $nomeMesa = $request->nome_mesa;
        $qtdLugar = $request->qtd_lugares;
        $numMesa = $request->num_mesa;
        $mesaAtiva = $request->ativo;

        //Try catch para verificar se a mesa foi atualizada com sucesso
        try {
            $mesa->nome_mesa = $nomeMesa;
            $mesa->qtd_lugares = $qtdLugar;
            $mesa->num_mesa = $numMesa;
            $mesa->ativo = $mesaAtiva;
            $mesa->save();

            return response()->json([
                'data' => $mesa,
                'idMesa' => $idMesa,
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    /**
     * Função para listar as mesas por evento
     *
     * @param  int  $idEvento
     */
    public function list(int $idEvento)
    {
        $mesas = Mesa::where('id_evento', $idEvento);

        $order = false;
        // Ordenação nome de mesa de AZ
        if (isset($_GET['order'])) {
            if ($_GET['order'] === 'AZ') {
                $mesas->orderBy('nome_mesa', 'asc');
            }

            $order = true;
        }

        $mesas = $mesas->get();

        $informacoes = array();

        $mesasunicas = array_column($mesas->toArray(), 'id');

        foreach ($mesasunicas as $index => $mesa) {
            $convidado = Convidado::where('id_mesa', $mesa)->orderBy('numero_da_cadeira', 'asc')->get();
            $convidados = $convidado->toArray();

            $informacao['nome_mesa'] = $mesas[$index]->nome_mesa;
            $informacao['qtd_lugares'] = $mesas[$index]->qtd_lugares;
            $informacao['lugares_disponiveis'] = $mesas[$index]->qtd_lugares - count($convidados);
            $informacao['num_mesa'] = $mesas[$index]->num_mesa;
            $informacao['id_evento'] = $mesas[$index]->id_evento;
            $informacao['id_mesa'] = $mesas[$index]->id;
            $informacao['ativo'] = $mesas[$index]->ativo;


            foreach ($convidado as $index => $val) {
                if ($this->visitou($idEvento, $val->email)) {
                    $convidado[$index]['visitou'] = true;
                } else {
                    $convidado[$index]['visitou'] = false;
                }
            }

            if (! is_null($convidado)) {
                $convidado = $convidado->sortBy('numero_da_cadeira')->toArray();
                $convidado = array_values($convidado);
            }

            $informacao['convidados'] = $convidado;

            $informacoes[] = $informacao;
        }

        // ordenação mesas vazias primeiro
        if ($order and $_GET['order'] == 'QNTD') {
            // Obtain a list of columns
            foreach ($informacoes as $key => $row) {
                $volume[$key] = $row['lugares_disponiveis'];
            }

            $volume = array_column($informacoes, 'lugares_disponiveis');

            array_multisort($volume, SORT_DESC, $informacoes);
        }

        return view(
            'mesa.index',
            [
                'data' => $informacoes,
                'idEvento' => $idEvento,
                'numeroDeConvidados' => $this->quantidadeDeConvidados($idEvento)
            ]
        );
    }

    /**
     * Função para listar as mesas por id da mesa
     *
     * @param  int  $idMesa
     */
    public function getMesa(int $idMesa)
    {
        $mesas = Mesa::where('id', $idMesa);

        // implementar ordenação
        $mesas = $mesas->get();

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
            $informacao['ativo'] = $mesas[$index]->ativo;
            $informacao['convidados'] = $convidados;

            $idEvento = $mesas[$index]->id_evento;

            $informacoes[] = $informacao;
        }

        return view(
            'mesa.list',
            [
                'data' => $informacoes,
                'idEvento' => $idEvento
            ]
        );
    }

    /**
     * Função para listar as mesas por evento
     *
     * @param  int  $idEvento
     */
    public function listAll(int $idEvento)
    {
        $mesas = Mesa::where('id_evento', $idEvento);

        $order = false;
        // Ordenação nome de mesa de AZ
        if (isset($_GET['order'])) {
            if ($_GET['order'] === 'AZ') {
                $mesas->orderBy('nome_mesa', 'asc');
            }

            $order = true;
        }

        $mesas = $mesas->get();

        $informacoes = array();

        $mesasunicas = array_column($mesas->toArray(), 'id');

        foreach ($mesasunicas as $index => $mesa) {
            $convidado = Convidado::where('id_mesa', $mesa)->orderBy('numero_da_cadeira', 'asc')->get();
            $convidados = $convidado->toArray();

            $informacao['nome_mesa'] = $mesas[$index]->nome_mesa;
            $informacao['qtd_lugares'] = $mesas[$index]->qtd_lugares;
            $informacao['lugares_disponiveis'] = $mesas[$index]->qtd_lugares - count($convidados);
            $informacao['num_mesa'] = $mesas[$index]->num_mesa;
            $informacao['id_evento'] = $mesas[$index]->id_evento;
            $informacao['id_mesa'] = $mesas[$index]->id;
            $informacao['ativo'] = $mesas[$index]->ativo;

            foreach ($convidado as $index => $val) {
                if ($this->visitou($idEvento, $val->email)) {
                    $convidado[$index]['visitou'] = true;
                } else {
                    $convidado[$index]['visitou'] = false;
                }
            }

            if (! is_null($convidado)) {
                $convidado = $convidado->sortBy('numero_da_cadeira')->toArray();
                $convidado = array_values($convidado);
            }

            $informacao['convidados'] = $convidado;

            $informacoes[] = $informacao;
        }

        // ordenação mesas vazias primeiro
        if ($order and $_GET['order'] == 'QNTD') {
            // Obtain a list of columns
            foreach ($informacoes as $key => $row) {
                $volume[$key] = $row['lugares_disponiveis'];
            }

            $volume = array_column($informacoes, 'lugares_disponiveis');

            array_multisort($volume, SORT_DESC, $informacoes);
        }

        return view(
            'mesa.edit',
            [
                'data' => $informacoes,
                'idEvento' => $idEvento,
                'numeroDeConvidados' => $this->quantidadeDeConvidados($idEvento)
            ]
        );
    }

    /**
     * @param  Request  $request
     * @param  int  $idEvento
     * @throws Exception
     */
    public function gerarMesas(Request $request, int $idEvento)
    {
        ini_set('max_execution_time', '300');
        $evento = Eventos::where('id', $idEvento)->first();
        $qntdDeLugares = $request->numeroCadeira;

        if (is_null($evento)) {
            throw new Exception('Esse evento não existe!');
        }

        $collection = collect(Cache::get('gerar_mesa_convidados' . $evento->id_guest_list));
        // $collection = collect($this->sharpSpringService->getAllMembersOfList($evento->id_guest_list));

        if ($collection->isEmpty()) {
            $collection = collect($this->sharpSpringService->getAllMembersOfList($evento->id_guest_list));

            if (count($collection) < 1) {
                throw new Exception('A lista não possui convidados');
            }

            Cache::put('gerar_mesa_convidados' . $evento->id_guest_list, $collection, 5000);
        }

        $convidadosProcessado = [];

        foreach ($collection->toArray() as $convidado) {
            $lead = Cache::get('lead_data' . $convidado['emailAddress']);

            if (is_null($lead)) {
                $lead = $this->sharpSpringService->getLead(null, $convidado['emailAddress']);
                Cache::forever('lead_data' . $convidado['emailAddress'], $lead);
            }

            if (blank($lead)) {
                $convidado['n__mero_mesa_6390e75b47239'] = null;
                $convidado['nome_mesa_6390e75b4ab7d'] = null;
                $lead = $convidado;
            }

            $convidadosProcessado[] = $lead;
        }

        $cadeirasDoSharp =  array_values(array_unique(array_column($convidadosProcessado, 'n__mero_mesa_6390e75b47239')));
        $mesasDoSharp = array_values(array_unique(array_column($convidadosProcessado, 'nome_mesa_6390e75b4ab7d')));

        foreach ($mesasDoSharp as $index => $mesa) {
            if (blank($mesa)) {
                unset($mesasDoSharp[$index]);
            }
        }
        $mesasDoSharp = array_values($mesasDoSharp);

        $full = true;
        $numeroDeCadeiras = 0;
        $contMesa = 0;
        $mesas = [];
        // GERAR AS MESAS
        foreach ($convidadosProcessado as $convidado) {
            if ($full) {
                $nomeDaMesa = null;

                if (isset($mesasDoSharp[$contMesa])) {
                    if (! blank($mesasDoSharp[$contMesa])){
                        $nomeDaMesa = $mesasDoSharp[$contMesa];
                    }
                }

                $mesas[] = $this->criarMesa($idEvento, $qntdDeLugares, $nomeDaMesa);
                $contMesa++;
            }

            if ($numeroDeCadeiras < $qntdDeLugares) {
                $numeroDeCadeiras++;
                $full = false;
            } else {
                $full = true;
                $numeroDeCadeiras = 0;
            }
        }

        foreach ($mesas as $mesa) {

            $maximoDeConvidados = 0;
            $this->proximaCadeira = 0;
            foreach ($convidadosProcessado as $index => $convidado) {

                if ($maximoDeConvidados <= $qntdDeLugares) {
                    if ($mesa['nome_mesa'] != $convidado['nome_mesa_6390e75b4ab7d']) {
                        if (!blank($convidado['n__mero_mesa_6390e75b47239'])) {
                            continue;
                        }
                    }

                    if (blank($convidado['n__mero_mesa_6390e75b47239'])) {
                        $convidado['n__mero_mesa_6390e75b47239'] = $this->proximaCadeira;
                        $this->proximaCadeira++;
                    }

                    $nome = $convidado['firstName'] . ' ' . $convidado['lastName'];
                    $email = $convidado['emailAddress'];
                    $empresa = $convidado['companyName'];

                    if (is_null($email)) {
                        $email = 'E-mail não informado';
                    }

                    if (is_null($nome)) {
                        $nome = 'Nome não informado';
                    }

                    if (is_null($empresa)) {
                        $empresa = 'Empresa não informada';
                    }

                    $full = $this->criarConvidado($mesa->id, $email, $nome, $empresa);

                    if (! $full) {
                        break;
                    } else {
                        $maximoDeConvidados++;
                    }

                    unset($convidadosProcessado[$index]);
                }
            }
        }

        return redirect()->route('get-mesas', [
            'idEvento' => $idEvento,
            'sucesso' => true
        ]);
    }

    /**
     * @param  int  $idEvento
     * @return int
     */
    private function quantidadeDeConvidados(int $idEvento): int
    {
        $evento = Eventos::where('id', $idEvento)->first();

        if (is_null($evento)) {
            return 0;
        }

        $collection = Cache::get('quantidade_de_convidados' . $evento->id_guest_list);

        if (is_null($collection)) {
            $collection = collect($this->sharpSpringService->getAllMembersOfList($evento->id_guest_list))->count();

            if ($collection < 1) {
                return 0;
            }

            Cache::put('quantidade_de_convidados' . $evento->id_guest_list, $collection, 5000);
        }

        return $collection;
    }

    /**
     * @param  int  $idEvento
     * @param  int  $qntdDeLugares
     * @param  string|null  $nomeMesa
     * @return ?Mesa
     */
    private function criarMesa(int $idEvento, int $qntdDeLugares, ?string $nomeMesa): ?Mesa
    {
        $mesa = new Mesa();

        $ultimoNumeroLivre = $mesa->where('id_evento', $idEvento)->max('num_mesa');

        if ($ultimoNumeroLivre == null || $ultimoNumeroLivre == 0) {
            $proximoNumeroLivre = 1;
        } else {
            $proximoNumeroLivre = $ultimoNumeroLivre + 1;
        }

        if (is_null($nomeMesa)) {
            $nomeMesa = 'Mesa ' . $proximoNumeroLivre;
        }

        $numMesa = $proximoNumeroLivre;
        $idEvento = $idEvento;

        if ($numMesa == null) {
            $numMesa = Mesa::count() + 1;
        }

        try {
            $mesa->nome_mesa = $nomeMesa;
            $mesa->qtd_lugares = $qntdDeLugares;
            $mesa->num_mesa = $numMesa;
            $mesa->id_evento = $idEvento;
            $mesa->save();

            return $mesa;
        } catch (Exception $e) {
            throw new Exception('Não foi possivel criar a mesa');
        }
    }

    private function criarConvidado(int $idMesa, string $email, string $nome, string $empresa = null): mixed
    {
        $convidado = new Convidado();

        $mesa = Mesa::where('id', $idMesa)->first();
        $idEvento = $mesa->id_evento;

        $convidadosDaMesa = Convidado::where('id_mesa', $mesa->id)->count();

        $mesaCheia = $convidadosDaMesa >= $mesa->qtd_lugares;

        if ($mesaCheia) {
            return false;
        }

        $this->proximaCadeira = $this->proximaCadeira + 1;

        try {
            $convidado->company_name = $empresa;
            $convidado->email = $email;
            $convidado->id_mesa = $idMesa;
            $convidado->nome_convidado = $nome;
            $convidado->numero_da_cadeira = $this->proximaCadeira;
            $convidado->save();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function visitou(int $listId, string $email): bool
    {
        $collection = collect(Cache::get('estatistica_' . $listId));

        if ($collection->isEmpty()) {
            $collection = Dashboards::where('event_id', $listId)->get();
        }

        if ($collection->isEmpty()) {
            return false;
        }

        $this->storeCollectionOnDatabase($collection, $listId);

        $collectionReturn = [];

        $collection->each(function ($item) use (&$collectionReturn) {
            $collectionReturn[] = $item['emailAddress'];
        });

        return in_array($email, $collectionReturn);
    }

    private function getEmpresa(int $listId, string $email): mixed
    {
        $collection = collect(Cache::get('estatistica_' . $listId));

        if ($collection->isEmpty()) {
            $collection = Dashboards::where('event_id', $listId)->get();
        }

        if ($collection->isEmpty()) {
            return false;
        }

        $this->storeCollectionOnDatabase($collection, $listId);

        $collectionReturn = [];

        $collection->each(function ($item) use (&$collectionReturn, $email) {
            if ($item['emailAddress'] == $email) {
                $collectionReturn[] = $item['companyName'];
            }
        });

        return $collectionReturn;
    }

    private function storeCollectionOnDatabase(Collection $collection, int $listId)
    {
        $exists = Dashboards::where('event_id', $listId)->get();

        if (! $exists->isEmpty()) {
            return;
        }

        $collection->each(function ($item) use ($listId) {
            Dashboards::create([
                'event_id' => $listId,
                'data' => json_encode($item)
            ]);
        });
    }
}
