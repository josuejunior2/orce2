<div class="modal modal-blur fade" id="modal-update-cidades-sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="modal-title">Adicionar cidades via planilha</div>
            <p>Envie um arquivo .xlsx com os seguintes dados em cada coluna:</p>
            <div class="col markdown mb-3">
                <ol>
                <li>Cidade;</li>
                <li>UF do estado.</li>
                </ol>
            </div>
            <form id="form_add_cidade_sheet_{{ $fornecedor->id }}" method="post" action="{{ route('fornecedor.update.cidades', ['fornecedor' => $fornecedor]) }}" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="mb-3">
                    {{-- <div class="form-label"></div> --}}
                    <input type="file" name="cidades_sheet" id="cidades_sheet" class="form-control" />
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
