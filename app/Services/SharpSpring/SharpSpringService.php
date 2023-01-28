<?php

namespace App\Services\SharpSpring;

use App\Repository\SharpSpring\SharpSpringRepositoryInterface;
use App\Repository\Evento\EventoRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use TypeError;

class SharpSpringService
{
    protected $repository;
    protected $eventos;

    public function __construct(
        SharpSpringRepositoryInterface $repository,
        EventoRepositoryInterface $eventos
    ) {
        $this->eventos = $eventos;
        $this->repository = $repository;
    }

    public function store(array $data)
    {
        unset($data['_token']);
        $data = $data['account_id'] . ',' . $data['secret_key'];
        return $this->repository->store($data);
    }

    public function getCredentials()
    {
        return $this->repository->getCredentials();
    }

    public function getGuest(int $sharpId, int $guestList): bool
    {
        $lead = $this->repository->getLead($sharpId);
        if (isset($lead['result']['lead'][0]['emailAddress'])) {
            $leadId = $lead['result']['lead'][0]['id'];
            $emailLead = $lead['result']['lead'][0]['emailAddress'];
            $list = $this->repository->getListMemberships($emailLead);
        } else {
            return false;
        }

        foreach ($list as $listId) {
            if (isset($listId['id'])) {
                if ($listId['id'] == $guestList) {
                    $idVisitorsList = $this->eventos->getByGuest($guestList)
                        ->first()->id_visitor_list;

                    return $this->repository->addListMember($leadId, $idVisitorsList);
                }
            }
        }

        return false;
    }

    public function getGuestByEmail(string $emailAddress, int $guestList): mixed
    {
        if (!empty($emailAddress)) {
            $list = $this->repository->getListMemberships($emailAddress);
        } else {
            return false;
        }

        foreach ($list as $listId) {
            if (isset($listId['id'])) {
                if ($listId['id'] == $guestList) {
                    $idVisitorsList = $this->eventos->getByGuest($guestList)
                        ->first()->id_visitor_list;

                    if ($this->repository->addListMemberEmailAddress($emailAddress, $idVisitorsList)) {
                        return $this->repository->getLead(null, $emailAddress);
                    }
                }
            }
        }

        return false;
    }

    public function getCountVisitor(int $guestList): array
    {
        $idVisitorsList = $this->eventos->getByGuest($guestList)->first()->id_visitor_list;

        if (!empty($guestList)) {
            $guestListData = $this->repository->getActiveList($guestList);
        } else {
            return false;
        }

        if (!empty($idVisitorsList)) {
            $visitorsListData = $this->repository->getActiveList($idVisitorsList);
        } else {
            return false;
        }

        $guestListCount = $guestListData['result']['activeList'][0]['memberCount'];
        $visitorListCount = $visitorsListData['result']['activeList'][0]['memberCount'];

        return [
            'convidados' => $guestListCount,
            'visitantes' => $visitorListCount
        ];
    }

    public function getAllMembersOfList(int $listId): array
    {
        $result = Cache::get('membros_da_lista_' . $listId);

        if (blank($result)) {
            $result = $this->repository->getAllMembersOfList($listId);
            Cache::put('membros_da_lista_' . $listId, $result, 5000);
        }

        if (blank($result)) {
            return [];
        }

        if (config('paniccontrol.mesa_sharp_field.panic')) {
            foreach ($result as $idOnly) {
                $arrayIdChunk[] = array_column($idOnly, 'leadID');
            }

            $arrayIdChunk = $this->mergeItself($arrayIdChunk);
            $arrayIdChunk = array_chunk($arrayIdChunk, 500);

            $result = Cache::get('lista_processada_para_o_cache_' . $listId);
            if (blank($result)) {
                ini_set('max_execution_time', '300');
                $result = $this->repository->getLeads($arrayIdChunk);
                Cache::put('lista_processada_para_o_cache_' . $listId, $result, 5000);
            }
        }

        $result = $this->mergeItself($result);

        return $result ?? [];
    }

    public function getLead(int $id = null, string $emailAddress = null): mixed
    {

        if ($id == null && $emailAddress == null) {
            return null;
        }

        $lead = $this->repository->getLead($id, $emailAddress);

        if (isset($lead['result']['lead'][0])) {
            return $lead['result']['lead'][0];
        }

        return [];
    }

    /**
     * @return mixed
     */
    public function getFields(): mixed
    {
        return $this->repository->getFields();
    }

    /**
     * Merge array itself
     *
     * @param  array  $arrays
     * @return array
     */
    private function mergeItself(array $arrays): array
    {
        $merged = [];

        foreach ($arrays as $array) {
            if (isset($array['result']['lead'])) {
                $merged = array_merge($merged, $array['result']['lead']);
            } else {
                if (!is_null($array)) {
                    $merged = array_merge($merged, $array);
                }
            }
        }

        return $merged;
    }
}
