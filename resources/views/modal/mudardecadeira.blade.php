<!-- Modal Nova Mesa -->
<div class="modal fade" id="mudarCadeiraConvidadoModal" tabindex="-1" aria-labelledby="mudarCadeiraConvidadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mudar de mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Cadeira atual <span class="badge bg-primary" id="cadeiraAtualModal">New</span></h6>

                <form method="POST" action="{{ route('muda-convidado-cadeira', ['idEvento' => $idEvento]) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="numeroCadeira" class="form-label">Numero da cadeira</label>
                        <input type="text" class="form-control" id="numeroCadeira" name="numero_da_cadeira"
                               aria-describedby="numeroCadeiraHelp" required>
                        <div id="numeroCadeiraHelp" class="form-text">Numero da Cadeira</div>
                    </div>
                    <input type="hidden" name="idConvidado" id="mudar_cadeira_id_convidado">
                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
