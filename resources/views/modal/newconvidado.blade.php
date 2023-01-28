<!-- Modal Nova Mesa -->
<div class="modal fade" id="newConvidadoModal" tabindex="-1" aria-labelledby="newConvidadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Convidado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('new-convidado') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nomeConvidado" class="form-label">Nome do convidado</label>
                        <input type="text" class="form-control" id="nomeConvidado" name="nome_convidado"
                               aria-describedby="nomeConvidadoHelp" required>
                        <div id="nomeConvidadoHelp" class="form-text">Nome do convidado</div>
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
                    <input type="hidden" name="id_mesa" id="convidado_id_mesa">
                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
