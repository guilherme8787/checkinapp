@extends('layouts.appstandalone')

@section('content')

<div id="dashboard" class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    {{-- <div class="row">
                        <div class="col-md-12">
                            Graficos disponíveis:
                            @foreach ($indexesOnly as $indexes)
                                <input class="form-check-input" type="checkbox" id="{{ $indexes }}Checkbox"/>
                                <label class="form-check-label" for="flexCheckDefault">{{ $indexes }}</label>
                            @endforeach
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <div id="chart">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="job">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="city">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="state">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="country">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="allData">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script>

    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Agrupamento'],
        },
        series: [],
        title: {
            text: 'Realtime',
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

    var chart = new ApexCharts(
        document.querySelector("#chart"),
        options
    );

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

            var series = [
                {
                    name: 'Convidados',
                    data: [ parseInt(response.convidados) ]
                },
                {
                    name: 'Visitantes',
                    data:[ parseInt(response.visitantes) ]
                }
            ];

            chart.updateSeries(series)
        });
    }

    setTimeout(function() { reRenderChart(); }, 5000);

</script>

@endsection
