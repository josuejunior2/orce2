<div class="modal modal-blur fade w-55" id="modal-edit-status-cotacao-{{$cotacao->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-title">Alterar status da cotação</div>
          <form method="POST" id="form_altera_status_{{$cotacao->id}}" action="{{ route('cotacao.altera.status', ['cotacao' => $cotacao]) }}" enctype="multipart/form-data">
            @csrf
            <select class="form-select" name="status" id="select-status-{{ $cotacao->id }}" value="{{ $cotacao->status }}">
                <option class="badge bg-yellow text-white" value="Em aberto">Em aberto</option>
                <option class="badge bg-green text-white" value="Fechado">Fechado</option>
              </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('form_altera_status_{{$cotacao->id}}').submit()">Alterar status</button>
            </div>
          </form>
      </div>
    </div>
</div>
