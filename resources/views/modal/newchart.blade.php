<div class="modal fade" id="newChart" tabindex="-1" aria-labelledby="newChartLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo gráfico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('new-chart', ['eventId' => $eventId, 'listId' => $listId]) }}">
                    @csrf

                    <div class="mb-3">
                        <select class="form-select" id="selectTheField" aria-label="Selecione um dos indices disponíveis" name="index" required>
                            <option value="0" selected>Selecione o campo do Sharp</option>
                            @foreach ($indexesOnly as $indexes)
                                <option value="{{ $indexes }}">{{ $indexes }}</option>
                            @endforeach
                        </select>
                        <label for="selectTheField">Selecione um dos indices disponíveis</label>
                    </div>

                    <div class="mb-3">
                        <select class="form-select" id="selectTheType" aria-label="Selecione um dos tipos disponíveis" name="type" required>
                            <option value="0" selected>Selecione o campo do Sharp</option>
                            <option value="bar">Barra</option>
                            <option value="pizza">Pizza</option>
                            <option value="arvore">Arvore</option>
                        </select>
                        <label for="selectTheType">Selecione um dos tipos disponíveis</label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="float: right;">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
