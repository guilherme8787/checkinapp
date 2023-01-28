@extends('layouts.appstandalone')

@section('content')

<div id="dashboard" class="container">

    @if ($sucesso ?? '' || isset($_GET['sucesso']))
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    Sucesso!
                </div>
            </div>
        </div>
    @endif

    @if ($error ?? '' || isset($_GET['error']))
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    {{ $error ?? $_GET['error']}}
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Menu</div>

                <div class="card-body">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-light">
                        Home
                    </a>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newChart">
                        Adicionar gráfico
                    </button>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div id="chart">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="staticData">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="job">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="city">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="state">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="country">
                            </div>
                        </div>
                    </div>


                    <div id="generatedCharts" class="row">
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script>

    var options = {
        series: [{{ $staticData }}],
        chart: {
            height: 350,
            type: 'radialBar',
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 225,
                    hollow: {
                        margin: 0,
                        size: '70%',
                        background: '#fff',
                        image: undefined,
                        imageOffsetX: 0,
                        imageOffsetY: 0,
                        position: 'front',
                        dropShadow: {
                            enabled: true,
                            top: 3,
                            left: 0,
                            blur: 4,
                            opacity: 0.24
                        }
                    },
                    track: {
                        background: '#fff',
                        strokeWidth: '67%',
                        margin: 0, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: -3,
                            left: 0,
                            blur: 4,
                            opacity: 0.35
                        }
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            offsetY: -10,
                            show: true,
                            color: '#888',
                            fontSize: '17px'
                        },
                        value: {
                            formatter: function(val) {
                                return parseInt(val);
                            },
                            color: '#111',
                            fontSize: '36px',
                            show: true,
                        }
                    }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#ABE5A1'],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            lineCap: 'round'
        },
        labels: ['Visitantes'],
    };

    var static = new ApexCharts(document.querySelector("#staticData"), options);
    static.render();

    var options = {
        series: [0],
        chart: {
            height: 350,
            type: 'radialBar',
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 225,
                    hollow: {
                        margin: 0,
                        size: '70%',
                        background: '#fff',
                        image: undefined,
                        imageOffsetX: 0,
                        imageOffsetY: 0,
                        position: 'front',
                        dropShadow: {
                            enabled: true,
                            top: 3,
                            left: 0,
                            blur: 4,
                            opacity: 0.24
                        }
                    },
                    track: {
                        background: '#fff',
                        strokeWidth: '67%',
                        margin: 0, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: -3,
                            left: 0,
                            blur: 4,
                            opacity: 0.35
                        }
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            offsetY: -10,
                            show: true,
                            color: '#888',
                            fontSize: '17px'
                        },
                        value: {
                            formatter: function(val) {
                                return parseInt(val);
                            },
                            color: '#111',
                            fontSize: '36px',
                            show: true,
                        }
                    }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#ABE5A1'],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            lineCap: 'round'
        },
        labels: ['Convidados'],
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Cargos'],
        },
        series: <?php echo $jobData ?>,
        title: {
            text: 'Cargos',
        },
        noData: {
            text: 'Loading...'
        },
        yaxis: [
            {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }
        ]
    }

    var job = new ApexCharts(
        document.querySelector("#job"),
        options
    );

    job.render();


    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Cities'],
        },
        series: <?php echo $cityData ?>,
        title: {
            text: 'Cities',
        },
        noData: {
            text: 'Loading...'
        },
        yaxis: [
            {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }
        ]
    }

    var city = new ApexCharts(
        document.querySelector("#city"),
        options
    );

    city.render();

    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['States'],
        },
        series: <?php echo $stateData ?>,
        title: {
            text: 'States',
        },
        noData: {
            text: 'Loading...'
        },
        yaxis: [
            {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }
        ]
    }

    var state = new ApexCharts(
        document.querySelector("#state"),
        options
    );

    state.render();

    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Country'],
        },
        series: <?php echo $countryData ?>,
        title: {
            text: 'Country',
        },
        noData: {
            text: 'Loading...'
        },
        yaxis: [
            {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }
        ],
    }

    var country = new ApexCharts(
        document.querySelector("#country"),
        options
    );

    country.render();

    function reRenderChart() {
        var url = '{{ route('dashboard-count', $eventId) }}';

        $.getJSON(url, function(response) {

            var series = [parseInt(response.convidados)];

            chart.updateSeries(series)
        });
    }

    reRenderChart();

    function reRenderChartVisitanteLocal() {
        var url = '{{ route('dashboard-count-real', ['listId' =>  $listId, 'eventId' => $eventId]) }}';

        $.getJSON(url, function(response) {

            var series = [parseInt(response.visitantes)];

            static.updateSeries(series)
        });
    }

    var charts = [];

    function apenasNumeros(str) {
        return parseInt(str.split(/\D+/).join(""), 10);
    }

    function normalizaNome(name) {
        let array = name.split('_');
        let nomeFinal = '';

        array.forEach(element => {
            if (!apenasNumeros(element) && element != '') {
                nomeFinal += ' ' + element[0].toUpperCase() + element.substring(1);
            }
        });

        return nomeFinal;
    }

    function barTypeGen(idName, json){
        textName = normalizaNome(idName);

        let data = JSON.parse(json);

        let options = {
            chart: {
                height: 350,
                type: 'bar',
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: [idName],
            },
            series: data.data,
            title: {
                text: textName,
            },
            noData: {
                text: 'Loading...'
            },
            yaxis: [
                {
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                }
            ],
            // colors: data.colors
        }

        let chart = new ApexCharts(
            document.querySelector("#"+idName),
            options
        );

        chart.render();

        charts[idName] = chart;
    }

    function barTypeGenReRender(idName, json){
        let data = JSON.parse(json);

        let options = {
            xaxis: {
                categories: [idName],
            },
            series: data.data,
            // colors: data.colors
        }

        charts[idName].updateOptions(options);
    }

    function treeTypeGen(idName, json) {
        textName = normalizaNome(idName);

        let data = JSON.parse(json);

        let options = {
                series: [data.data],
                legend: {
                show: false
            },
            chart: {
                height: 350,
                type: 'treemap'
            },
            title: {
                text: textName
            },
            // colors: data.colors
        };

        let chart = new ApexCharts(document.querySelector("#" + idName), options);
        chart.render();

        charts[idName] = chart;
    }

    function treeTypeGenReRender(idName, json) {
        let data = JSON.parse(json);

        let options = {
            series: [data.data],
            title: {
                text: idName
            },
            // colors: data.colors
        };

        charts[idName].updateOptions(options);
    }

    function pieTypeGen(idName, json) {
        textName = normalizaNome(idName);

        let data = JSON.parse(json);

        let options = {
            series: data.series,
            chart: {
                width: 380,
                type: 'pie',
            },
            labels: data.labels,
            title: {
                text: textName,
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            // colors: data.colors
        };

        let chart = new ApexCharts(document.querySelector("#" + idName), options);
        chart.render();

        charts[idName] = chart;
    }

    function pieTypeGenReRender(idName, json) {
        textName = normalizaNome(idName);

        let data = JSON.parse(json);

        let options = {
            series: data.series,
            labels: data.labels,
            title: {
                text: textName,
            },
            // colors: data.colors
        };

        charts[idName].updateOptions(options);
    }

    function addElement(idName){
        const div = document.createElement('div');

        div.className = 'col-md-6';
        div.id = 'id' + idName;

        div.innerHTML = `
            <div id="` + idName + `">
            </div>
        `;

        document.getElementById('generatedCharts').appendChild(div);
    }

    function generate(json) {
        if (json.length === 0) {
            return;
        }

        json.forEach(element => {
            addElement(element.index);
            getChartData(element.type, element.index);
        });
    }

    function generateReRender(json) {
        if (json.length === 0) {
            return;
        }

        json.forEach(element => {
            getChartDataReRender(element.type, element.index);
        });
    }

    function dataChartManager(type, index, json){
        if (type === 'bar') {
            barTypeGen(index, json);
        }

        if (type === 'arvore') {
            treeTypeGen(index, json);
        }

        if (type === 'pizza') {
            pieTypeGen(index, json);
        }
    }

    function dataChartManagerReRender(type, index, json){
        if (type === 'bar') {
            barTypeGenReRender(index, json);
        }

        if (type === 'arvore') {
            treeTypeGenReRender(index, json);
        }

        if (type === 'pizza') {
            pieTypeGenReRender(index, json);
        }
    }

    function getPersonalCharts() {
        fetch('{{ route('get-charts', $listId) }}')
            .then((response) => response.json())
            .then((data) => generate(data));
    }

    function getPersonalChartsReRender() {
        fetch('{{ route('get-charts', $listId) }}')
            .then((response) => response.json())
            .then((data) => generateReRender(data));
    }

    function getChartData(type, index) {
        fetch(`{{ URL::to('/') }}` + '/get-chart-data/' + '{{ $listId }}' + '/' + type + '/' + index)
            .then((response) => response.json())
            .then((data) => dataChartManager(type, index, data));
    }

    function getChartDataReRender(type, index) {
        fetch(`{{ URL::to('/') }}` + '/get-chart-data/' + '{{ $listId }}' + '/' + type + '/' + index)
            .then((response) => response.json())
            .then((data) => dataChartManagerReRender(type, index, data));
    }

    setInterval(() => {
        reRenderChartVisitanteLocal();
        getPersonalChartsReRender();
    }, 10000);

    setInterval(() => {
        reRenderChart();
    }, 600000);

    getPersonalCharts();

</script>

@include('modal.newchart')
@endsection
