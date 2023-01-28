<?php

namespace App\Repository\SharpSpring;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SharpSpringRepositoryRest implements SharpSpringRepositoryInterface
{
    protected $storage;

    public function __construct(Storage $storage)
    {
        return $this->storage = $storage;
    }

    public function getCredentials(): array
    {
        $string = $this->storage::get('sharp-credentials.txt');
        $array = explode(',', $string);
        return [
            'account_id' => $array[0],
            'secret_key' => $array[1]
        ];
    }

    private function sharpRest(string $method, array $params): mixed
    {
        $data = json_encode([
            'method' => $method,
            'params' => $params,
            'id' => session_id(),
        ]);

        $credentials = $this->getCredentials();

        $queryString = http_build_query([
            'accountID' => $credentials['account_id'],
            'secretKey' => $credentials['secret_key'],
        ]);

        $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

        $response = Http::withBody($data, 'application/json')
            ->withOptions([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($data)
                ]
            ])
            ->post($url);

        return json_decode($response, true);
    }

    public function store(string $data)
    {
        return $this->storage::put('sharp-credentials.txt', $data);
    }

    /**
     * @param string $email leadEmail
     */
    public function getListMemberships(string $email): array
    {
        $params = [
            'emailAddress' => $email,
        ];
        $method = 'getListMemberships';

        $result = $this->sharpRest($method, $params);

        try {
            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param int $id listId
     */
    public function getListMembers(int $id): array
    {
        $params = [
            'id' => $id,
        ];
        $method = 'getListMembers';

        $result = $this->sharpRest($method, $params);

        try {
            return $result['result']['getWherelistMemberGets'];
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param int $leadId leadId
     * @param int $listID listId
     */
    public function addListMember(int $leadId, int $listID): bool
    {
        $params = [
            'memberID' => $leadId,
            'listID' => $listID
        ];
        $method = 'addListMember';

        $result = $this->sharpRest($method, $params);

        if (isset($result['result']['creates'][0]['success'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $leadId leadId
     * @param int $listID listId
     */
    public function addListMemberEmailAddress(string $emailAddress, int $listID): bool
    {
        $params = [
            'emailAddress' => $emailAddress,
            'listID' => $listID
        ];
        $method = 'addListMemberEmailAddress';

        $result = $this->sharpRest($method, $params);

        $leadExistsOnList = false;
        $errorMessageIfExists = 'The lead you tried to add is already a member';
        $leadExistsOnList = false;
        if (isset($result['error']['message'])) {
            $leadExistsOnList = $result['error']['message'] == $errorMessageIfExists;
        }

        $resultIsTrue = isset($result['result']['creates'][0]['success']);

        if ($leadExistsOnList || $resultIsTrue) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getLead(?int $id = null, ?string $email = null): array
    {
        if (is_null($id) and is_null($email)) {
            throw new Exception('Id ou Email não informado ao getLeads in' . self::class);

            return [];
        }

        $params = [
            'id' => $id,
        ];

        $method = 'getLead';

        if (!is_null($email)) {
            $params = [
                'where' => [
                    'emailAddress' => $email
                ],
                'limit' => 1,
                'offset' => 0
            ];

            $method = 'getLeads';
        }
        $result = $this->sharpRest($method, $params);

        try {
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @param int $id listId
     */
    public function getActiveList(int $id): mixed
    {
        $params = [
            'where' => [
                'id' => $id,
            ]
        ];
        $method = 'getActiveLists';

        $result = $this->sharpRest($method, $params);

        try {
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @param int $listId
     */
    public function getAllMembersOfList(int $listId): mixed
    {
        $offset = 0;
        $limit = 1000;

        $params = [
            'where' => [
                'id' => $listId,
            ],
            'limit' => 1000,
        ];

        $method = 'getListMembers';

        $result = $this->sharpRest($method, $params);

        if (!isset($result['result']['getWherelistMemberGets'])) {
            return [];
        }

        $finalArray[] = $result['result']['getWherelistMemberGets'];

        if (isset($result['hasMore'])) {
            if ($result['hasMore']) {
                $flag = true;

                while ($flag) {
                    $flag = false;
                    $offset = $offset + $limit;

                    $params = [
                        'where' => [
                            'id' => $listId,
                        ],
                        'offset' => $offset,
                        'limit' => $limit,
                    ];

                    $result = $this->sharpRest($method, $params);
                    $finalArray[] = $result['result']['getWherelistMemberGets'];

                    if (isset($result['hasMore'])) {
                        if ((bool) $result['hasMore']) {
                            $flag = true;
                        }
                    }
                }
            }
        }

        return $finalArray ?? [];
    }

    public function getFields(): mixed
    {
        $params = [
            'where' => [],
        ];

        $method = 'getFields';

        try {
            return $this->sharpRest($method, $params);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public function getLeads(array $listOfIds = []): array
    {
        $offset = 0;
        $limit = 1000;
        $method = 'getLeads';

        if (count($listOfIds) == 1) {
            $lists = $listOfIds[0];

            if (!blank($lists)) {
                $params = [
                    'where' => [
                        'id' => $lists,
                    ],
                    'limit' => $limit,
                    'offset' => $offset
                ];
            }

            $finalArray[] = $this->sharpRest($method, $params);
        } else {
            foreach ($listOfIds as $lists) {
                $lists = array_filter($lists);

                if (!blank($lists)) {
                    $params = [
                        'where' => [
                            'id' => $lists,
                        ],
                        'limit' => $limit,
                        'offset' => $offset
                    ];
                }

                $finalArray[] = $this->sharpRest($method, $params);
            }
        }

        return $finalArray ?? [];
    }
}
