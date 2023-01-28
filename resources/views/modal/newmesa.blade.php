<!-- Modal Nova Mesa -->
<div class="modal fade" id="newMesaModal" tabindex="-1" aria-labelledby="newMesaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('new-mesa', ['idEvento' => $idEvento]) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="nomeMesa" class="form-label">Nome da mesa</label>
                        <input type="text" class="form-control" id="nomeMesa" name="nome_mesa"
                               aria-describedby="nomeHelp" required>
                        <div id="mesaHelp" class="form-text">Nome da mesa</div>
                    </div>
                    <div class="mb-3">
                        <label for="qtdLugares" class="form-label">Qtd. lugares</label>
                        <input type="number" class="form-control" id="qtdLugares" name="qtd_lugares"
                               aria-describedby="lugaresHelp" required>
                        <div id="lugaresHelp" class="form-text">Quantidade de lugares na mesa</div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="numMesa" class="form-label">Número da mesa</label>
                        <input type="number" class="form-control" id="numMesa" name="num_mesa"
                               aria-describedby="numHelp" required>
                        <div id="numHelp" class="form-text">Número da mesa</div>
                    </div> --}}
                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
