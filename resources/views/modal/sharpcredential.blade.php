<!-- Modal de credenciais do sharp spring -->
<div class="modal fade" id="ssCredentialsModal" tabindex="-1" aria-labelledby="ssCredentialsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Credenciais do Sharp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p class="mark">Ao clicar em enviar suas credenciais atuais serão substituidas!</p>

                <form method="POST" action="{{ route('new-sharp-key') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="idOfAccount" class="form-label">Id da conta</label>
                        <input type="text" class="form-control" id="idOfAccount" name="account_id">
                    </div>
                    <div class="mb-3">
                        <label for="secretKey" class="form-label">Secret key</label>
                        <input type="text" class="form-control" id="secretKey" name="secret_key">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
