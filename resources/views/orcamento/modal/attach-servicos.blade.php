<div class="modal modal-blur fade mw-50" id="modal-attach-servicos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="modal-title">
                Atribuir serviços aos sites
                <small class="form-hint">
                    Após atribuir, basta clicar em cima do serviço para remover.
                </small>
            </div>
            <form id="form_attach_servicos" method="post" action="{{ route('servico.attach.sites', ['orcamento' => $orcamento]) }}">
                @method('POST')
                @csrf
                <div class="mb-3">
                    <label class="col-3 col-form-label required" for="servicos">Serviços</label>
                    <div class="col">
                        <input type="text" name="servicos" id="servicos" multiple required></input>
                    </div>
                </div> 
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label class="col-3 col-form-label required" for="sites">Sites</label>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn" id="btn-todos">Todos</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="text" name="sites" id="sites" multiple required></input>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="reset">
                        <span class="form-check-label">Remover todos e atribuir novamente</span>
                    </label>
                </div>     
        </div>                                   
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
      </div>
    </div>
</div>
