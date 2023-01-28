@extends('layouts.app')

@section('content')
<div class="container">

    @if ($sucesso ?? '' || isset($_GET['sucesso']))
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-success" role="alert">
                    Sucesso!
                </div>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Menu</div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#newEventModal">
                        Novo Evento
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ssCredentialsModal">
                        Credenciais do Sharp
                    </button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Eventos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Bem vindo!') }}

                    @if (isset($eventos))
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col">Url para o evento</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Deletar</th>
                                    <th scope="col">Ver</th>
                                    <th scope="col">Dashboard</th>
                                    <th scope="col">Mesas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventos as $index => $val)
                                <tr>
                                    {{-- <th scope="row">{{ $val['id'] }}</th> --}}
                                    <td>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="text{{ $val['id'] }}"
                                            value="{{ route('credenciamento', ['listId' => $val['id'], 'guestEventId' => $val['id_guest_list']]) }}"
                                        />
                                    </td>
                                    <td>{{ $val['event_description'] }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('delete-event', $val['id']) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('get-list', $val['id']) }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard', ['eventId' => $val['id_guest_list'], 'listId' => $val['id']]) }}" class="btn btn-sm btn-warning" target="_BLANK">
                                            <i class="bi bi-bar-chart-fill"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('get-mesas', $val['id']) }}" class="btn btn-sm btn-light" target="_BLANK">
                                            Mesas
                                        </a>
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

@include('modal.newevent')
@include('modal.sharpcredential')

@endsection
