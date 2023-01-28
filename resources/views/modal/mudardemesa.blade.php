<!-- Modal Nova Mesa -->
<div class="modal fade" id="mudarMesaConvidadoModal" tabindex="-1" aria-labelledby="mudarMesaConvidadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mudar de mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <h6>Mesa Atual <span class="badge bg-primary" id="cadeiraAtualModal">New</span></h6>

                    <form method="POST" action="{{ route('muda-convidado-mesa', ['idEvento' => $idEvento]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="numeroMesa" class="form-label">Numero da mesa</label>
                            <input type="text" class="form-control" id="numeroMesa" name="numero_da_mesa"
                                   aria-describedby="numeroMesaHelp" required>
                            <div id="numeroMesaHelp" class="form-text">Numero da mesa</div>
                        </div>
                        <input type="hidden" name="idConvidado" id="mudar_mesa_id_convidado">
                        <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
