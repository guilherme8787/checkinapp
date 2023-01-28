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
                    <div class="card-header">Menu</div>
                    <div class="dropdown">
                        <div class="card-body">
                            <a href="{{ route('home') }}" class="btn btn-light">
                                Home
                            </a>
                            <a href="{{ route('list-mesas', $idEvento) }}" class="btn btn-light">
                                Editar Mesas
                            </a>
                            <a href="{{ route('list-guests', $idEvento) }}" class="btn btn-light">
                                Editar Convidados
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#newMesaModal">
                                Nova Mesa
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#gerarMesasAutomagicamente">
                                Gerar Mesas
                            </button>
                            <a href="{{ route('get-relatorio-mesa', $idEvento) }}" class="btn btn-success">
                                Relatório CSV
                            </a>
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Ordenar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('get-mesas', $idEvento) }}?order=AZ" class="dropdown-item">Ordem Alfabética</a></li>
                                <li><a href="{{ route('get-mesas', $idEvento) }}?order=QNTD" class="dropdown-item">Quantidade</a></li>
                                <li><a href="{{ route('get-mesas', $idEvento) }}" class="dropdown-item">Limpar ordenação</a></li>
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
        <div class="row justify-content-center px-4">
            @foreach ($data as $mesa)
                @if($mesa['ativo'] == 1)
                    <div class="col-4 py-4">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="line-height: 35px">
                                        <form method="POST" action="{{ route('delete-mesa', $mesa['id_mesa']) }}">
                                           @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        style="float: right; margin-right: 5px">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                        </form>
                                        <a type="button" class="btn btn-sm btn-primary"
                                           href="{{ route('get-mesa', $mesa['id_mesa']) }}"
                                           style="float: right; margin-right: 5px">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="document.getElementById('convidado_id_mesa').value = '{{ $mesa['id_mesa'] }}'"
                                            data-bs-toggle="modal"
                                            data-bs-target="#newConvidadoModal"
                                            style="float: right; margin-right: 5px">
                                            <i class="bi bi-person-plus"></i>
                                        </button>

                                        <h5>
                                            <span class="badge bg-primary">#{{ $mesa['num_mesa'] }} - {{ $mesa['nome_mesa'] }}</span>
                                        </h5>
                                        <h5>
                                            <span class="badge bg-secondary">
                                                Lugares Ocupados / Total: [{{ $mesa['qtd_lugares'] - $mesa['lugares_disponiveis'] }} / {{ $mesa['qtd_lugares'] }}]
                                            </span>
                                        </h5>
                                        @if ($mesa['lugares_disponiveis'] < 1)
                                        <h5>
                                            <span class="badge bg-danger">Mesa lotada</span>
                                        @endif
                                        @if($mesa['ativo'] == 1)
                                            <span class="badge bg-success" style="float: right">Mesa Ativa</span>
                                        @else
                                            <span class="badge bg-danger" style="float: right">Mesa Inativa</span>
                                        @endif
                                        </h5>
                                    </div>
                                    <div class="card-body" style="height: 300px;overflow: auto;">
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <div class="dropdown">
                                            <table class="table table-responsive" style="vertical-align: middle;">
                                                <tbody>
                                                    @if (count($mesa['convidados']) > 0)
                                                        @foreach ($mesa['convidados'] as $convidado)
                                                            @if ($convidado['visitou'])
                                                            <tr style="background: #42b883;color:#35495e;">
                                                            @else
                                                            <tr>
                                                            @endif
                                                                <td>{{ $convidado['numero_da_cadeira'] }}</td>
                                                                <td>{{ $convidado['nome_convidado'] }} / {{ $convidado['company_name'] }}</td>
                                                                <td>
                                                                    @if ($convidado['visitou'])
                                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                                            style="float: right"></button>
                                                                    @else
                                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                                            style="float: right"></button>
                                                                    @endif
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <button class="btn btn-sm btn-warning dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#mudarMesaConvidadoModal"
                                                                                style="float: right"
                                                                                onclick="document.getElementById('mudar_mesa_id_convidado').value={{ $convidado['id'] }}">
                                                                                Mudar de mesa
                                                                            </button>
                                                                            <button class="btn btn-sm btn-warning dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#mudarCadeiraConvidadoModal"
                                                                                style="float: right"
                                                                                onclick="document.getElementById('mudar_cadeira_id_convidado').value={{ $convidado['id'] }}">
                                                                                Mudar cadeira
                                                                            </button>
                                                                            <button class="btn btn-sm btn-warning dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#newAcompanhanteModel"
                                                                                style="float: right"
                                                                                onclick="preencherConvidadoAcomanhante({{ $mesa['id_mesa'] }}, {{ $idEvento }}, {{ $convidado['id'] }})">
                                                                                Adicionar acompanhante
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

@include('modal.newmesa')
@include('modal.newconvidado')
@include('modal.mudardemesa')
@include('modal.mudardecadeira')
@include('modal.gerarmesas')
@include('modal.newacompanhante')

@endsection
