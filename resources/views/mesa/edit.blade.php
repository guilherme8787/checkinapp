@extends('layouts.app')
@section('content')
    <div>
        @if ($success ?? '' || isset($_GET['success']))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-success" role="alert">
                        {{ $success ?? $_GET['success']}}
                    </div>
                </div>
            </div>
        @endif

        @if ($sucesso ?? '' || isset($_GET['sucesso']))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-success" role="alert">
                        Sucesso!
                    </div>
                </div>
            </div>
        @endif

        @if ($error ?? '' || isset($_GET['error']))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger" role="alert">
                        {{ $error ?? $_GET['error']}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Menu</div>
                    <div class="dropdown">
                        <div class="card-body">
                            <a href="{{ route('home') }}" class="btn btn-light">
                                Home
                            </a>
                            <a href="{{ route('get-mesas', $idEvento) }}" class="btn btn-light">
                                Voltar
                            </a>
                            <a href="{{ route('get-relatorio-mesa', $idEvento) }}" class="btn btn-success">
                                Relatório CSV
                            </a>
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Ordenar Mesas
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('list-mesas', $idEvento) }}?order=AZ" class="dropdown-item">Ordem Alfabética</a></li>
                                <li><a href="{{ route('list-mesas', $idEvento) }}?order=QNTD" class="dropdown-item">Quantidade</a></li>
                                <li><a href="{{ route('list-mesas', $idEvento) }}" class="dropdown-item">Limpar ordenação</a></li>
                            </ul>
                            <button onclick="window.location.reload();" class="btn btn-success">
                                <i class="bi bi-arrow-counterclockwise"></i> Recarregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    <div id="table" class="container">
        <form method="POST" action="{{ route('post-mesas', $idEvento) }}">
            @csrf
        <table class="table table-striped table-bordered table-hover" style="text-align: center">
            <thead>
                <tr>
                    <th>Nome da Mesa</th>
                    <th>Qtd. Lugares</th>
                    <th>Lugares Disponíveis</th>
                    <th>Número da Mesa</th>
                    <th>ID da Mesa</th>
                    <th>ID do Evento</th>
                    <th>Ativo?</th>
                    <th>Salvar</th>
                </tr>
            </thead>
            <tbody>
                <button type="submit" class="btn btn-success btn-lg"
                    style="float: right; margin-bottom: 10px">Salvar Todos</button>
                @foreach($data as $mesa)
                    <tr>
                        <td>
                            <input type="hidden" name="id[]" value="{{ $mesa['id_mesa'] }}">
                            <input type="text" class="form-control" id="nomeMesa{{ $mesa['id_mesa'] }}"
                                   name="nome_mesa{{ $mesa['id_mesa'] }}"
                                   aria-describedby="nomeHelp" value="{{ $mesa['nome_mesa'] }}">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="qtdLugares{{ $mesa['id_mesa'] }}"
                                   name="qtd_lugares{{ $mesa['id_mesa'] }}"
                                   aria-describedby="lugaresHelp" value="{{ $mesa['qtd_lugares'] }}">
                        </td>
                        <td id="lugaresDisponiveis">{{ $mesa['lugares_disponiveis'] }}</td>
                        <td>
                            <input type="text" class="form-control" id="numMesa{{ $mesa['id_mesa'] }}"
                                   name="num_mesa{{ $mesa['id_mesa'] }}"
                                   aria-describedby="numHelp" value="{{ $mesa['num_mesa'] }}">
                        </td>
                        <td id="idMesa">{{ $mesa['id_mesa'] }}</td>
                        <td id="idEvento">{{ $mesa['id_evento'] }}</td>
                        <td>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                    id="ativo{{ $mesa['id_mesa'] }}" name="ativo{{ $mesa['id_mesa'] }}"
                                    aria-describedby="ativoHelp">
                                @if($mesa['ativo'] == 1)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <span class="btn btn-success"
                                    onclick="atualizaMesa({{ $mesa['id_mesa'] }})">
                                Salvar
                            </span>
                        </td>
                    </tr>
                @endforeach
                <script>
                    function atualizaMesa(idMesa) {
                        fetch('{{ url('/api/mesa/edit') }}/' + idMesa, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                nome_mesa: document.getElementById("nomeMesa" + idMesa).value,
                                qtd_lugares: document.getElementById("qtdLugares" + idMesa).value,
                                num_mesa: document.getElementById("numMesa" + idMesa).value,
                                ativo: document.getElementById("ativo" + idMesa).value,
                            }),
                        })
                            .then((response) => response.json())
                            .then((json) => successAlertMesaUpdated(json))
                            .catch((err) => console.log(err));
                    }

                    function successAlertMesaUpdated(json) {
                        if (json.success) {
                            alert('Mesa atualizada');
                        } else {
                            alert('Não foi possivel atualizar a mesa, ' + json.message);
                        }
                    }
                </script>
            </tbody>
        </table>
        </form>
    </div>
@endsection

