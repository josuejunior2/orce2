<div class="modal modal-blur fade w-55" id="modal-edit-status-orcamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-title">Alterar status do orçamento</div>
          <form method="POST" id="form_altera_status_{{$orcamento->id}}" action="{{ route('orcamento.altera.status', ['orcamento' => $orcamento]) }}" enctype="multipart/form-data">
            @csrf
            <select class="form-select" name="status" id="select-status-{{ $orcamento->id }}" value="{{ $orcamento->status }}">
                <option class="badge bg-yellow text-white" value="Em cotação">Em cotação</option>
                <option class="badge bg-blue text-white" value="Enviado">Enviado</option>
                <option class="badge bg-green text-white" value="Aprovado">Aprovado</option>
                <option class="badge bg-red text-white" value="Sem viabilidade">Sem viabilidade</option>
              </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('form_altera_status_{{$orcamento->id}}').submit()">Alterar status</button>
            </div>
          </form>
      </div>
    </div>
</div>
