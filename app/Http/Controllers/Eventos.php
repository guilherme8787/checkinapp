<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Evento\EventoService;
use App\Traits\ResponseHttpStatusCode;
use Exception;

class Eventos extends Controller
{
    use ResponseHttpStatusCode;

    public const PATH = '/var/www/html/public/upload';

    private $service;

    public function __construct(EventoService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // event_bg_image block
            if ($request->has('event_bg_image')) {
                $file = $request->file('event_bg_image');
                $file->move(self::PATH, $file->getClientOriginalName());
                $data['event_bg_image'] = $file->getClientOriginalName();
            }

            // image block
            if ($request->has('event_image')) {
                $file = $request->file('event_image');
                $file->move(self::PATH, $file->getClientOriginalName());
                $data['event_img'] = $file->getClientOriginalName();
            }

            unset($data['has_qr_code_in_credential']);
            unset($data['event_image']);
            unset($data['_token']);

            $this->service->store($data);

            return redirect()->route('home', ['sucesso' => true]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getList()
    {
        try {
            return $this->service->getList();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function event(int $listId, int $eventId)
    {
        try {
            $data = $this->service->get($listId);

            return view(
                'preauto.index',
                [
                    'eventId' => $eventId,
                    'data' => $data->toArray()
                ]
            );
        } catch(Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            if($this->service->destroy($id)){
                return redirect()->route('home', ['sucesso' => true]);
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}


