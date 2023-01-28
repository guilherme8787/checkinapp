<!-- Modal Nova Mesa -->
<div class="modal fade" id="gerarMesasAutomagicamente" tabindex="-1" aria-labelledby="gerarMesasAutomagicamenteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mudar de mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>É possivel gerar mesas para a lista de convidados do evento,
                    desde que a lista não exceda <span class="badge bg-danger">5000</span>
                    convidados
                </h6>

                <p>Numero de convidados:  <span class="badge bg-success" id="numeroDeConvidados">{{ $numeroDeConvidados }}</span></p>

                <div id="calculoDeMesa">
                    <span id="">Isso vai gerar essa quantidade de mesas:  <span class="badge bg-warning" id="totalPorMesa">0</span> <span style="cursor: pointer;" class="badge bg-info">Ver</span></span>
                    <br>
                    <br>
                </div>

                <form method="POST" action="{{ route('table.gen', ['eventId' => $idEvento]) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="numeroCadeira" class="form-label">Numero da cadeiras por mesa</label>
                        <input type="integer" class="form-control" id="numeroCadeiraGerarMesa" name="numeroCadeira"
                               aria-describedby="numeroCadeiraHelp" onchange="calcularQuantasMesas(this.value)" required>
                        <div id="numeroCadeiraHelp" class="form-text">Quantas cadeiras vc deseja por mesa?</div>
                    </div>
                    <input type="hidden" name="idConvidado" id="mudar_cadeira_id_convidado">
                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

function calcularQuantasMesas(numeroDeCadeiras) {
    numeroCadeira = document.getElementById('numeroCadeiraGerarMesa').value;
    console.log(numeroCadeira);
    let numeroDeConvidados = document.getElementById('numeroDeConvidados').innerText;
    let quantidadeDeMesasPossiveis = Math.ceil(numeroDeMesas(numeroDeConvidados, numeroDeCadeiras));

    document.getElementById('calculoDeMesa').style = "display:block;";
    document.getElementById('totalPorMesa').innerText= quantidadeDeMesasPossiveis;
}

function numeroDeMesas(numeroDeConvidados, numeroDeCadeiras) {
    let mesasPorCadeiras = parseInt(1);
    let partialPercentage =  parseInt(numeroDeConvidados) / parseInt(numeroDeCadeiras);

  return partialPercentage;
}

</script>
