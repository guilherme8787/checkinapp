<!-- Modal Nova Mesa -->
<div class="modal fade" id="newAcompanhanteModel" tabindex="-1" aria-labelledby="newAcompanhanteModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar convidado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('adicionar-convidado-acompanhante') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nomeConvidado" class="form-label">Nome do acompanhante</label>
                        <input type="text" class="form-control" id="nomeConvidado" name="nome_convidado"
                               aria-describedby="nomeConvidadoHelp" required>
                        <div id="nomeConvidadoHelp" class="form-text">Nome do acompanhante</div>
                    </div>
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Empresa</label>
                        <input type="text" class="form-control" id="companyName" name="company_name"
                               aria-describedby="companyNameHelp" required>
                        <div id="companyNameHelp" class="form-text">Empresa</div>
                    </div>
                    <div class="mb-3">
                        <label for="emailConvidado" class="form-label">E-mail do convidado</label>
                        <input type="text" class="form-control" id="emailConvidado" name="email"
                               aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">E-mail do convidado</div>
                    </div>

                    <input type="hidden" name="id_mesa" id="acompanhante_id_mesa">
                    <input type="hidden" name="id_evento" id="acompanhante_id_evento">
                    <input type="hidden" name="id_convidado" id="acompanhante_id_convidado">

                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    function preencherConvidadoAcomanhante(idMesa, idEvento, idConvidado){
        document.getElementById('acompanhante_id_mesa').value = idMesa;
        document.getElementById('acompanhante_id_evento').value = idEvento;
        document.getElementById('acompanhante_id_convidado').value = idConvidado;
    }

</script>
