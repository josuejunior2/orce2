<div class="modal modal-blur fade" id="modal-destroy-fornecedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-title">Você tem certeza?</div>
          <div>Se proceder, você irá perder os dados do fornecedor e as cotações que ele participa.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" onclick="document.getElementById('form_{{$fornecedor->id}}').submit()">Sim, excluir fornecedor</button>
        </div>
      </div>
    </div>
</div>
