<!-- Modal novo evento -->
<div class="modal fade" id="newEventModal" tabindex="-1" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('new-event') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="listOfGuest" class="form-label">Id da lista de convidados</label>
                        <input type="number" class="form-control" id="listOfGuest" name="id_guest_list" aria-describedby="guestHelp" required>
                        <div id="guestHelp" class="form-text">Lista dos seus convidados</div>
                    </div>
                    <div class="mb-3">
                        <label for="listOfVisitors" class="form-label">Id da lista de presentes</label>
                        <input type="number" class="form-control" id="listOfVisitors" name="id_visitor_list"
                            aria-describedby="VisitorsHelp" required>
                        <div id="VisitorsHelp" class="form-text">Lista para onde vão os seus visitantes</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescriptionhelp" class="form-label">Descrição do evento</label>
                        <input type="text" class="form-control" id="eventDescription" name="event_description"
                            aria-describedby="eventDescriptionHelp" required>
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
                            aria-describedby="eventColor">
                        <div id="eventColor" class="form-text">Cor do fundo</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventColor" class="form-label">Cor da fonte</label>
                        <input type="color" class="form-control" id="eventFontColor" name="font_color"
                            aria-describedby="eventColor" value="#FFFFFF">
                        <div id="eventColor" class="form-text">Cor da fonte</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventUrlSubhelp" class="form-label">Link de inscrição</label>
                        <input type="text" class="form-control" id="eventUrlSub" name="url_inscricao"
                            aria-describedby="eventUrlSubHelp">
                        <div id="eventUrlSubHelp" class="form-text">Link de inscrição</div>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="teclado" value="yes">
                        <label class="form-check-label">
                            Deseja exibir teclado para preenchimento de email?
                        </label>
                    </div>
                    <expiration-date-component></expiration-date-component>
                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
