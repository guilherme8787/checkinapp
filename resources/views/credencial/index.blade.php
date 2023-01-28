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
                <div class="card-header">Menu</div>

                <div class="card-body">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-light">
                        Home
                    </a>
                    <a class="btn btn-sm btn-info" href="{{ route('get-list', $listId) }}">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Selecione os campos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (! blank($data))
                        <table class="table table-responsive">
                            <tbody>
                                @foreach($data as $index => $val)
                                <tr>
                                    <td>{{ $val['systemName'] }}</td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="{{ $val['systemName'] }}" id="flexCheckDefault">
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
