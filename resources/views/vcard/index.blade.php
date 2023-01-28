<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CheckinApp') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body id="app" style="color: {{ is_null($corFonte) ? 'brown' : $corFonte }}; background-color: {{ is_null($corFundo) ? 'brown' : $corFundo }};">
    <div class="header">
        <div class="limited-width container py-12 my-12 text-center shadow-header vcard-header">
            <div class="container py-4">
                <img class="card-avatar my-4" src="{{ url('/') }}/legacy/img/avatar.png" />
                <h4>{{ $nome }}</h4>
                <p class="sub-title">{{ $empresa }}<br>{{ $cargocracha }}</p>

            </div>
        </div>
        <div class="limited-width container text-center shadow-header" style="background: white; color: #000;">
            <div class="container py-4">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                {{ preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $telefone)) }}
                                <br><small><i>Telefone</i></small>
                            </th>
                        </tr>
                        <tr>
                            <th scope="row">
                                {{ $emailAddress }}
                                <br><small><i>Email</i></small>
                            </th>
                    </tbody>
                </table>
                <center>
                    <a href="{{ route('generate-vcard') }}?name={{ $nome }}&tel={{ preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $telefone)) }}&email={{ $emailAddress }}&empresa={{ $empresa }}&cargo={{ $cargocracha }}" class="btn btn-light mb-3">Salvar nos contatos</a>
                    <a target="_BLANK" href="https://wa.me/55{{ preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $telefone)) }}" class="btn btn-success mb-3">Whatsapp</a>
                </center>
            </div>
        </div>
        <br>
        <br>
    </div>

</body>

</html>

<style>
    .header {
        width: 100%;
        min-height: 35vh;
        position: fixed;
    }

    .card-avatar {
        height: 95px;
        width: 95px;
        border-radius: 50%;
        background-position: center center !important;
        background-size: cover !important;
        margin: 10px auto 0 auto;
    }

    .vcard-header {
        margin-top: 50px;
    }

    .sub-title {
        font-weight: 500;
    }

    .shadow-header {
        -webkit-box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.44);
        box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.1);
    }

    .limited-width {
        max-width: 570px !important;
    }
</style>
