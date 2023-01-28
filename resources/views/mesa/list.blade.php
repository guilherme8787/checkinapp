@extends('layouts.app')

@section('content')
    <div class="container">
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

        @if ($errors->any())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body" style="text-align: center">
                        <a href="{{ route('get-mesas', $idEvento) }}" class="btn btn-light"
                           style="margin-right: 100px">
                            Lista de Mesas
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editMesaModal">
                            Editar Mesa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Informações da mesa: {{ $data[0]['nome_mesa'] }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (isset($data))
                            <table class="table table-responsive">
                                <tbody>
                                @foreach ($data as $index => $mesa)
                                    <tr>
                                        <td>Nome da Mesa</td>
                                        <td>{{ $mesa['nome_mesa'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantidade de Lugares</td>
                                        <td>{{ $mesa['qtd_lugares'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Número da Mesa</td>
                                        <td>{{ $mesa['num_mesa'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>ID do Evento</td>
                                        <td>{{ $mesa['id_evento'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mesa Ativa?</td>
                                        <td>@if($mesa['ativo'] == 1) Sim @else Não @endif</td>
                                    </tr>
                                    <tr>
                                        <td>Convidados</td>
                                        <td>
                                        @foreach ($mesa['convidados'] as $convidado)
                                                    {{ $convidado }} <br>
                                        @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal nova lista -->
    <div class="modal fade" id="editMesaModal" tabindex="-1" aria-labelledby="editMesaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atualizar Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('edit-mesa', $data[0]['id_mesa']) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nomeMesa" class="form-label">Nome da mesa</label>
                            <input type="text" class="form-control" id="nomeMesa" name="nome_mesa"
                                   aria-describedby="nomeHelp" value="{{ $data[0]['nome_mesa'] }}">
                            <div id="nomeMesaHelp" class="form-text">Nome da mesa</div>
                        </div>
                        <div class="mb-3">
                            <label for="qtdLugares" class="form-label">Qtd. lugares</label>
                            <input type="number" class="form-control" id="qtdLugares" name="qtd_lugares"
                                   aria-describedby="lugaresHelp" value="{{ $data[0]['qtd_lugares'] }}">
                            <div id="lugaresHelp" class="form-text">Quantidade de lugares na mesa</div>
                        </div>
                        <div class="mb-3">
                            <label for="numMesa" class="form-label">Número da mesa</label>
                            <input type="number" class="form-control" id="numMesa" name="num_mesa"
                                   aria-describedby="numeroHelp" value="{{ $data[0]['num_mesa'] }}">
                            <div id="numeroHelp" class="form-text">Número da mesa</div>
                        </div>
                        <div class="mb-3">
                            <label for="ativo" class="form-label">Mesa Ativa?</label>
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
                            <div id="ativoHelp" class="form-text">A mesa está ativa?</div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float: right;">Atualizar</button>
                    </form>
                </div>
@endsection
