<?php

namespace App\Http\Controllers;

use App\Models\Dashboards;
use App\Models\DashboardConfigs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\SharpSpring\SharpSpringService;
use App\Traits\ResponseHttpStatusCode;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    use ResponseHttpStatusCode;

    private $service;

    public function __construct(SharpSpringService $service)
    {
        $this->service = $service;
    }

    public function visitorRealTime(int $eventId)
    {
        $data = $this->service->getCountVisitor($eventId);

        try {
            return response()->json($data, Response::HTTP_OK);
        } catch(Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function visitorRealTimeLocal(int $listId, int $eventId)
    {
        $collection = collect(Cache::get('estatistica_' . $listId));

        if (! $collection->isEmpty()) {
            $collection = collect(Cache::get('estatistica_' . $listId));
            $this->storeCollectionOnDatabase($collection, $listId);
        } else {
            $collection = Dashboards::where('event_id', $listId)->get();

            if ($collection->isEmpty()) {
                return response()->json(['visitantes' => 0], Response::HTTP_OK);
            }
        }

        $staticData = $collection->count();

        try {
            return response()->json(['visitantes' => $staticData], Response::HTTP_OK);
        } catch(Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function getVisitorDashboard(int $guestList)
    {
        return view(
            'dashboard.index',
            [
                'eventId' => $guestList
            ]
        );
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

    public function index(int $guestList, int $listId)
    {
        $collection = collect(Cache::get('estatistica_' . $listId));

        if (! $collection->isEmpty()) {
            $collection = collect(Cache::get('estatistica_' . $listId));
            $this->storeCollectionOnDatabase($collection, $listId);
        } else {
            $collection = Dashboards::where('event_id', $listId)->get();

            if ($collection->isEmpty()) {
                return view('dashboard.index3', [
                    'staticData' => '',
                    'jobData' => '',
                    'cityData' => '',
                    'countryData' => '',
                    'stateData' => '',
                    'indexesOnly' => [],
                    'allData' => '',
                    'eventId' => $guestList,
                    'listId' => $listId
                ]);
            }

            $collectionReturn = [];

            $collection->each(function ($item) use (&$collectionReturn) {
                $collectionReturn[] = json_decode($item->toArray()['data'], true);
            });

            $collection = collect($collectionReturn);
        }

        $staticData = $collection->count();
        $jobData = $collection->groupBy('title')->map->count();
        $cityData = $collection->groupBy('city')->map->count();
        $countryData = $collection->groupBy('country')->map->count();
        $stateData = $collection->groupBy('state')->map->count();

        $allData = isset($collection->toArray()[0]) ? $collection->toArray()[0] : [] ;
        $indexesOnly = array_keys($allData);

        return view('dashboard.index3', [
            'staticData' => $staticData,
            'jobData' => self::barDataSetFormater($jobData->toArray()),
            'cityData' => self::barDataSetFormater($cityData->toArray()),
            'countryData' => self::barDataSetFormater($countryData->toArray()),
            'stateData' => self::barDataSetFormater($stateData->toArray()),
            'indexesOnly' => $indexesOnly,
            'allData' => self::barDataSetFormater($allData),
            'eventId' => $guestList,
            'listId' => $listId
        ]);
    }

    private static function barDataSetFormater($vetor)
    {
        $final = [];
        foreach ($vetor as $key => $val) {
            $final[] = [
                'name' => empty($key) ? 'Não declarado' : $key,
                    'data' => [
                        $val
                    ]
                ];
        }

        return json_encode($final, true);
    }

    private static function barDataSetFormater2($vetor)
    {
        $final = [];
        $color = [];

        foreach ($vetor as $key => $val) {
            $final[] = [
                'name' => empty($key) ? 'Não declarado' : $key,
                    'data' => [[
                        'x' => '',
                        'y' => $val
                    ]]
                ];
            $color[] = self::randColor();
        }

        return json_encode(['data' => $final, 'colors' => $color], true);
    }

    private static function treeMapDataSetFormater($vetor)
    {
        $final = [];
        $color = [];

        foreach ($vetor as $key => $val) {
            $final[] = [
                'x' => empty($key) ? 'Não declarado' : $key,
                'y' => $val
            ];
            $color[] = self::randColor();
        }

        return json_encode(['data' => ['data' => $final], 'colors' => $color], true);
    }

    private static function pieDataSetFormater($vetor)
    {
        $final = [];
        $series = [];
        $labels = [];
        $color = [];

        foreach ($vetor as $key => $val) {
            $labels[] = empty($key) ? 'Não declarado' : $key;
            $series[] = $val;
            $color[] = self::randColor();
        }

        $final = [
            'series' => $series,
            'labels' => $labels,
            'colors' => $color
        ];

        return json_encode($final, true);
    }

    private static function randColor() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function newChart(Request $request, int $listId, int $eventId)
    {

        if ($request->all()['index'] === '0' || $request->all()['type'] === '0') {
            return redirect()->route('dashboard', [
                'eventId' => $eventId,
                'listId' => $listId,
                'error' => 'Você precisa não pode deixar nada em branco para gerar um novo gráfico!'
            ]);
        }

        $data = DashboardConfigs::where('event_id', $eventId)->get();

        $searchItem = $request->collect()->get('index');

        $founded = $data->filter(function ($item) use ($searchItem) {
            $item = json_decode($item->charts);

            return $item->index === $searchItem;
        });

        if (! $founded->isEmpty()) {
            DashboardConfigs::where('id', array_column($founded->toArray(), 'id')[0])
                ->update([
                    'charts' => $request->collect()->toJson()
                ]);
        } else {
            DashboardConfigs::create([
                'event_id' => $eventId,
                'charts' => $request->collect()->toJson()
            ]);
        }

        return redirect()->route('dashboard', [
            'eventId' => $listId,
            'listId' => $eventId,
            'sucesso' => 'O gráfico vai ser gerado.'
        ]);
    }

    public function getCharts(int $eventId)
    {
        $data = DashboardConfigs::where('event_id', $eventId)->get();

        $finalArray = [];

        $data->each(function ($item) use (&$finalArray) {
            $var = json_decode($item->charts, true);

            $finalArray[] = [
                'index' => $var['index'],
                'type' => $var['type']
            ];
        });

        return response()->json($finalArray, 200);
    }

    public function getChartData(int $listId, string $type, string $index)
    {
        $collection = collect(Cache::get('estatistica_' . $listId));

        if (! $collection->isEmpty()) {
            $collection = collect(Cache::get('estatistica_' . $listId));
            $this->storeCollectionOnDatabase($collection, $listId);
        } else {
            $collection = Dashboards::where('event_id', $listId)->get();

            if ($collection->isEmpty()) {
                return;
            }

            $collectionReturn = [];

            $collection->each(function ($item) use (&$collectionReturn) {
                $collectionReturn[] = json_decode($item->toArray()['data'], true);
            });

            $collection = collect($collectionReturn);
        }

        $data = $collection->groupBy($index)->map->count();

        if ($type === 'bar') {
            $data = self::barDataSetFormater2($data->toArray());
        }

        if ($type === 'arvore') {
            $data = self::treeMapDataSetFormater($data->toArray());
        }

        if ($type === 'pizza') {
            $data = self::pieDataSetFormater($data->toArray());
        }

        return response()->json($data, 200);
    }
}
