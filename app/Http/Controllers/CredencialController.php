<?php

namespace App\Http\Controllers;

use App\Repository\SharpSpring\SharpSpringRepositoryInterface;
use App\Repository\Evento\EventoRepositoryEloquent;
use App\Services\Evento\EventoService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CredencialController extends Controller
{
    private EventoService $eventoService;
    private SharpSpringRepositoryInterface $sharpRepository;

    /**
     * class contructor
     *
     * @param EventoService $eventoService
     * @param SharpSpringRepositoryInterface $repository
     */
    public function __construct(
        EventoService $eventoService,
        SharpSpringRepositoryInterface $sharpRepository
    ) {
        $this->eventoService = $eventoService;
        $this->sharpRepository = $sharpRepository;
    }
    /**
     * Return credential print page
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function printCredential(int $listId, string $hash): View
    {
        $data = Cache::get($hash);

        if (is_null($data)) {
            abort(404, 'expired');
        }

        $data = $data->toArray();

        if(isset($data['id'])){
            $nrvisitante = $data['id'];
        } else {
            $nrvisitante = '9999999999';
        }

        if(isset($data['firstName']) and $data['lastName']){
            $nome = strtoupper($data['firstName'] . ' ' . $data['lastName']);
        } else {
            $nome = 'Nome completo';
        }

        if(isset($data['companyName'])){
            $empresa = strtoupper($data['companyName']);
        } else {
            $empresa = 'Nome fantasia';
        }

        if(isset($data['title'])){
            $cargocracha = strtoupper($data['title']);
        } else {
            $cargocracha = 'Cargo  crachá';
        }

        $barcode = str_pad($nrvisitante, 10, "0", STR_PAD_LEFT);

        $urlqrcode  = 'd='.urlencode($barcode).'&';
        $urlqrcode .= 'e=H&';
        $urlqrcode .= 's=6&';
        $urlqrcode .= 't=P';

        // Tamanhos de fonte
        $tamanhonome    = '70px';
        $tamanhoempresa = '55px';
        $tamanhocargo   = '50px';

        if(strlen($nome) > 27 || strlen($cargocracha) > 33 || strlen($empresa) > 31){
          $tamanhonome    = '50px';
          $tamanhoempresa = '45px';
          $tamanhocargo   = '40px';
        }

        return view('preauto.etiqueta', [
            'emailAddress' => $data['emailAddress'],
            'nrvisitante' => $nrvisitante,
            'nome' => $nome,
            'empresa' => $empresa,
            'cargocracha' => $cargocracha,
            'barcode' => $barcode,
            'urlqrcode' => $urlqrcode,
            'tamanhonome' => $tamanhonome,
            'tamanhoempresa' => $tamanhoempresa,
            'tamanhocargo' => $tamanhocargo,
            'qrcode' => new \Picqer\Barcode\BarcodeGeneratorPNG(),
            'hash' => $hash,
            'listId' => $listId,
        ]);
    }

    public function editarCredencial(int $listId)
    {
        $fields = $this->sharpRepository->getFields()['result']['field'];

        return view(
            'credencial.index',
            [
                'listId' => $listId,
                'data' => $fields
            ]
        );

    }

    public function cardIndex(int $listId, string $hash)
    {
        $evento = $this->eventoService->get($listId)->toArray();

        $expirationDate = $evento[0]['credential_expiration_date'];
        $corFundo = $evento[0]['color'];
        $corFonte = $evento[0]['font_color'];

        if (is_null($expirationDate)) {
            abort(404, 'expired');
        }

        if ($expirationDate < now()) {
            abort(404, 'expired');
        }

        $date1 = Carbon::createFromFormat('Y-m-d', $expirationDate);
        $date2 = now();

        if(! $date1->gt($date2)){
            abort(404, 'expired');
        }

        $data = Cache::get($listId . '_' . $hash);

        if (is_null($data)) {
            abort(404, 'expired');
        }

        $data = $data->toArray();

        if(isset($data['firstName']) and $data['lastName']){
            $nome = $data['firstName'] . ' ' . $data['lastName'];
        } else {
            $nome = 'Nome completo';
        }

        if(isset($data['companyName'])){
            $empresa = $data['companyName'];
        } else {
            $empresa = 'Nome fantasia';
        }

        if(isset($data['title'])){
            $cargocracha = $data['title'];
        } else {
            $cargocracha = 'Cargo  crachá';
        }

        if(isset($data['mobilePhoneNumber'])){
            $telefone = $data['mobilePhoneNumber'];
        } else {
            $telefone = '11 5555-5555';
        }

        return view(
            'vcard.index',
            [
                'emailAddress' => $data['emailAddress'],
                'nome' => $nome,
                'empresa' => $empresa,
                'cargocracha' => $cargocracha,
                'telefone' => $telefone,
                'hash' => $hash,
                'listId' => $listId,
                'corFundo' => $corFundo,
                'corFonte' => $corFonte,
            ]
        );
    }
}
