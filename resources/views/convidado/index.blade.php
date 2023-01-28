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

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body" style="text-align: center">
                        <a href="{{ route('get-mesas', $idMesa) }}" class="btn btn-light"
                            style="margin-right: 100px">
                            Lista de Mesas
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editConvidadoModal">
                            Editar Convidado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Informações do Convidado: {{ $data[0]['email'] }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (isset($data))
                            <table class="table table-responsive">
                                <tbody>
                                @foreach ($data as $index => $convidado)
                                    <tr>
                                        <td>Email do Convidado</td>
                                        <td>{{ $convidado['email'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mesa do Convidado</td>
                                        <td>{{ $convidado['id_mesa'] }}</td>
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
    <div class="modal fade" id="editConvidadoModal" tabindex="-1" aria-labelledby="editConvidadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atualizar Convidado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('edit-convidado', $data[0]['id_convidado']) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="emailConvidado" class="form-label">Email do Convidado</label>
                            <input type="text" class="form-control" id="emailConvidado" name="email_convidado"
                                   aria-describedby="nomeHelp" value="{{ $data[0]['email'] }}">
                            <div id="emailHelp" class="form-text">Email do Convidado</div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float: right;">Atualizar</button>
                    </form>
                </div>
@endsection
