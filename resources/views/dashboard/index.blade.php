@extends('layouts.appstandalone')

@section('content')

<div id="dashboard" class="container">

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
