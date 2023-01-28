
<!doctype html>
<html lang="br">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Auto-atendimento</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='{{ url('/') }}/legacy/main.css'>
    <script src='{{ url('/') }}/legacy/main.js'></script>

    <link rel="stylesheet" href='{{ url('/') }}/legacy/assets/css/bootstrap.min.css'>
    <script src='{{ url('/') }}/legacy/assets/js/jquery-3.4.1.min.js'></script>
    <script src='{{ url('/') }}/legacy/assets/js/popper.min.js'></script>
    <script src='{{ url('/') }}/legacy/assets/js/bootstrap.min.js'></script>

    <link rel="icon" href="{{ url('/') }}/legacy/assets/img/favicon.gif" type="image/gif" sizes="16x16">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>
<body>

    <main class="py-4">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Mensagem</div>
                        <br>
                            @if ($error ?? '' || isset($_GET['error']))
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-danger" role="alert">
                                        Você não esta credenciado para este evento!
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Display the countdown timer in an element -->
                            <div class="row justify-content-center">
                                <div class="col-md-8 text-center">
                                    <p class="btn btn-light btn-lg" id="demo"></p>
                                </div>
                            </div>

                            <script>
                                function addMoreSeconds(numOfSeconds, date = new Date()) {
                                    date.setSeconds(date.getSeconds() + numOfSeconds);

                                    return date;
                                }

                                const date = new Date().getTime()
                                var countDownDate = addMoreSeconds(5);

                                var x = setInterval(function() {

                                    var now = new Date().getTime();
                                    var distance = countDownDate - now;
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                    document.getElementById("demo").innerHTML = seconds;
                                    if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demo").innerHTML = "REDIRECIONANDO";
                                        window.location = '{{ route('credenciamento', ['listId' => $listId, 'guestEventId' => $guestList]) }}';
                                    }
                                }, 1000);
                            </script>

                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
