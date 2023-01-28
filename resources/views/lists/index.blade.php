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
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editListtModal">
                        Editar Lista
                    </button>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Detalhes') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (! blank($data))
                        <table class="table table-responsive">
                            <tbody>
                                <tr>
                                    <td>Id</td>
                                    <td>{{ $data->id }}</td>
                                </tr>
                                <tr>
                                    <td>Descrição do evento</td>
                                    <td>{{ $data->event_description }}</td>
                                </tr>
                                <tr>
                                    <td>Data de expiração do QrCode da credencial</td>
                                    @if (! is_null($data->credential_expiration_date))
                                    <td>{{ date('d/m/Y', strtotime($data->credential_expiration_date)) }}</td>
                                    @else
                                    <td>QrCode v-card desativado</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Cor do fundo<br>
                                        <small>
                                            *Essa cor deverá ser aplicada ao fundo do evento e do vcard
                                        </small>
                                    </td>
                                    <td>{{ $data->color }} <div style="width:15px;height:15px;background:{{ $data->color }};border: 0.5px solid black;">&nbsp;</div></td>
                                </tr>
                                <tr>
                                    <td>Cor da fonte<br>
                                        <small>
                                            *Essa cor deverá ser aplicada a fonte do evento e do vcard
                                        </small>
                                    </td>
                                    <td>{{ $data->font_color }} <div style="width:15px;height:15px;background:{{ $data->font_color }};border: 0.5px solid black;">&nbsp;</div></td>
                                </tr>
                                <tr>
                                    <td>Lista de credenciados</td>
                                    <td>{{ $data->id_guest_list }}</td>
                                </tr>
                                <tr>
                                    <td>Lista de visitante</td>
                                    <td>{{ $data->id_visitor_list }}</td>
                                </tr>
                                <tr>
                                    <td>Banner do evento</td>
                                    @if (! is_null($data->event_img))
                                    <td><img class="img-fluid" style="max-width: 350px; object-fit: cover;" src="{{ asset('upload') . '/' . $data->event_img }}"></td>
                                    @else
                                    <td>A imagem do evento não foi enviada</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Background do evento</td>
                                    @if (! is_null($data->event_bg_image))
                                    <td><img class="img-fluid" style="max-width: 350px; object-fit: cover;" src="{{ asset('upload') . '/' . $data->event_bg_image }}"></td>
                                    @else
                                    <td>A imagem de background do evento não foi enviada</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Teclado</td>
                                    <td>{{ $data->teclado != 'yes' ? 'Não exibir' : 'Exibir' }}</td>
                                </tr>
                                <tr>
                                    <td>URL de inscrição</td>
                                    <td>{{ $data->url_inscricao }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal nova lista -->
<div class="modal fade" id="editListtModal" tabindex="-1" aria-labelledby="editListtModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova lista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('edit-list', $data->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="listOfGuest" class="form-label">Id da lista de convidados</label>
                        <input type="number" class="form-control" id="listOfGuest" name="id_guest_list" aria-describedby="guestHelp" value="{{ $data->id_guest_list }}">
                        <div id="guestHelp" class="form-text">Lista dos seus convidados</div>
                    </div>
                    <div class="mb-3">
                        <label for="listOfVisitors" class="form-label">Id da lista de presentes</label>
                        <input type="number" class="form-control" id="listOfVisitors" name="id_visitor_list"
                            aria-describedby="VisitorsHelp" value="{{ $data->id_visitor_list }}">
                        <div id="VisitorsHelp" class="form-text">Lista para onde vão os seus visitantes</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescriptionhelp" class="form-label">Descrição do evento</label>
                        <input type="text" class="form-control" id="eventDescription" name="event_description"
                            aria-describedby="eventDescriptionHelp" value="{{ $data->event_description }}">
                        <div id="eventDescriptionHelp" class="form-text">Edição</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventImageHelp" class="form-label">Imagem do evento</label>
                        <input type="file" class="form-control" id="eventImage" name="event_image"
                            aria-describedby="eventImageHelp">
                        <div id="eventImageHelp" class="form-text">Imagem do evento</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventBgImageHelp" class="form-label">Imagem do fundo</label>
                        <input type="file" class="form-control" id="eventBgImage" name="event_bg_image"
                            aria-describedby="eventBgImageHelp">
                        <div id="eventBgImageHelp" class="form-text">Imagem do fundo</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventColor" class="form-label">Cor do fundo</label>
                        <input type="color" class="form-control" id="eventColor" name="color"
                            aria-describedby="eventColor" value="{{ $data->color }}">
                        <div id="eventColor" class="form-text">Cor do fundo</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventColor" class="form-label">Cor da fonte</label>
                        <input type="color" class="form-control" id="eventFontColor" name="font_color"
                            aria-describedby="eventColor" value="{{ $data->font_color ?? '#FFFFFF' }}">
                        <div id="eventColor" class="form-text">Cor da fonte</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventUrlSubhelp" class="form-label">Link de inscrição</label>
                        <input type="text" class="form-control" id="eventUrlSub" name="url_inscricao" value="{{ $data->url_inscricao }}"
                            aria-describedby="eventUrlSubHelp">
                        <div id="eventUrlSubHelp" class="form-text">Link de inscrição</div>
                    </div>
                    <div class="mb-3 form-check">
                        @if ($data->teclado != 'yes')
                        <input class="form-check-input" type="checkbox" name="teclado" value="yes">
                        @else
                        <input class="form-check-input" type="checkbox" name="teclado" value="yes" checked>
                        @endif
                        <label class="form-check-label">
                            Deseja exibir teclado para preenchimento de email?
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        @if (is_null($data->credential_expiration_date))
                        <input class="form-check-input" type="checkbox" name="has_qr_code_in_credential" value="yes">
                        @else
                        <input class="form-check-input" type="checkbox" name="has_qr_code_in_credential" value="yes" checked>
                        @endif
                        <label class="form-check-label" for="flexCheckDefault">
                            Deseja QRCode v-card na credencial?
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="credentialExpireDate" class="form-label">Validade</label>
                        <input type="date" class="form-control" id="credentialExpireDate" name="credential_expiration_date"
                            aria-describedby="credentialExpireDate" value="{{ $data->credential_expiration_date }}"
                            required>
                        <div id="credentialExpireDate" class="form-text">Quando vence o QRCode da credencial?</div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="float: right;">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
