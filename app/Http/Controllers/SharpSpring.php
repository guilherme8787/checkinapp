<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Evento\EventoService;
use App\Services\SharpSpring\SharpSpringService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class SharpSpring extends Controller
{
    private $service;
    private $evento;

    public function __construct(
        EventoService $evento,
        SharpSpringService $service
    ) {
        $this->evento = $evento;
        $this->service = $service;
    }

    public function store(Request $request)
    {
        try {
            $this->service->store($request->all());

            return redirect()->route('home', ['sucesso' => true]);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getGuest(int $guestList, Request $request)
    {
        try {
            $sharpId = $request->get('visitorId');
            if($this->service->getGuest($sharpId, $guestList)) {
                return view('preauto.success', [
                    'success' => true,
                    'guestList' => $guestList
                ]);
            } else {
                return view('preauto.error', [
                    'error' => false,
                    'guestList' => $guestList
                ]);
            }
            dd($this->service->getGuest($sharpId, $guestList));
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getGuestByEmail(int $listId, int $guestList, Request $request)
    {
        try {
            $list = $this->evento->get($listId)->toArray();

            $emailAddress = $request->get('emailAddress');
            $emailAddress = str_replace('><', '@', $emailAddress);
            $hash = md5(Hash::make($emailAddress));

            if (Cache::get($hash)) {
                $leadData = Cache::get($hash)->toArray();
            } else {
                $leadData = $this->service->getGuestByEmail($emailAddress, $guestList);

                if ($leadData == false) {
                    return view('preauto.error', [
                        'error' => true,
                        'listId' => $listId,
                        'guestList' => $guestList
                    ]);
                }

                $leadData = $leadData['result']['lead'][0];

                Cache::put($hash, collect($leadData), now()->addMinutes(6));

                if (! is_null(Cache::get('estatistica_' . $listId))) {
                    $estatistica = Cache::get('estatistica_' . $listId);
                    $estatistica[] = collect($leadData);
                    Cache::forever('estatistica_' . $listId, $estatistica);
                } else {
                    $estatistica[] = collect($leadData);
                    Cache::forever('estatistica_' . $listId, $estatistica);
                }

                if (! is_null($list[0]['credential_expiration_date'])) {
                    Cache::put($listId . '_' . $hash, collect($leadData), Carbon::createFromFormat('Y-m-d', $list[0]['credential_expiration_date']));
                }
            }

            if($leadData) {
                return view('preauto.success', [
                    'success' => true,
                    'guestData' => $leadData,
                    'guestList' => $guestList,
                    'listId' => $listId,
                    'hash' => $hash
                ]);
            } else {
                return view('preauto.error', [
                    'error' => true,
                    'listId' => $listId,
                    'guestList' => $guestList
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getCredentials()
    {
        try {
            dd($this->service->getCredentials());
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
