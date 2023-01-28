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
            <form method="POST" action="{{ route('post-convidados', $idEvento) }}">
                @csrf
                <table class="table table-striped table-bordered table-hover" style="text-align: center">
                    <thead>
                    <tr>
                        <th>Nome do Convidado</th>
                        <th>E-mail do Convidado</th>
                        <th>Empresa</th>
                        <th>Número da Cadeira</th>
                        <th>ID da Mesa</th>
                        <th>Salvar</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    <button type="submit" class="btn btn-success btn-lg"
                            style="float: right; margin-bottom: 10px">Salvar Todos</button>
                    @foreach($data as $convidado)
                        <tr>
                            <td>
                                <input type="hidden" name="id[]" value="{{ $convidado['id_convidado'] }}">
                                <input type="text" class="form-control" id="nomeConvidado{{ $convidado['id_convidado'] }}"
                                       name="nome_convidado{{ $convidado['id_convidado'] }}"
                                       aria-describedby="nomeHelp" value="{{ $convidado['nome_convidado'] }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="email{{ $convidado['id_convidado'] }}"
                                       name="email{{ $convidado['id_convidado'] }}"
                                       aria-describedby="emailHelp" value="{{ $convidado['email'] }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="companyName{{ $convidado['id_convidado'] }}"
                                       name="company_name{{ $convidado['id_convidado'] }}"
                                       aria-describedby="companyNameHelp" value="{{ $convidado['company_name'] }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="numCadeira{{ $convidado['id_convidado'] }}"
                                       name="numero_da_cadeira{{ $convidado['id_convidado'] }}"
                                       aria-describedby="numCadeiraHelp" value="{{ $convidado['numero_da_cadeira'] }}">
                            </td>
                            <td>{{ $convidado['id_mesa'] }}</td>
                            <td>
                                <span class="btn btn-success"
                                        onclick="atualizaConvidado({{ $convidado['id_convidado'] }})">
                                    Salvar
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    <script>
                        function atualizaConvidado(idConvidado) {
                            fetch('{{ url('/api/convidado/edit') }}/' + idConvidado, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({
                                    nome_convidado: document.getElementById("nomeConvidado" + idConvidado).value,
                                    email: document.getElementById("email" + idConvidado).value,
                                    company_name: document.getElementById("companyName" + idConvidado).value,
                                    numero_da_cadeira: document.getElementById("numCadeira" + idConvidado).value,
                                }),
                            })
                                .then((response) => response.json())
                                .then((json) => successAlertConvidadoUpdated(json))
                                .catch((err) => console.log(err));
                        }

                        function successAlertConvidadoUpdated(json) {
                            if (json.success) {
                                alert('Convidado atualizado com sucesso!');
                            } else {
                                alert('Não foi possivel atualizar o convidado, ' + json.message);
                            }
                        }
                    </script>
                    </tbody>
                </table>
            </form>
        </div>
@endsection

