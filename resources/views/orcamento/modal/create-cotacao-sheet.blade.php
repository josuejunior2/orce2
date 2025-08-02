<div class="modal modal-blur fade mw-50" id="modal-create-cotacao-sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="modal-title">Adicionar cotações via planilha</div>
            <p>Envie um arquivo .xlsx com os seguintes dados em cada coluna:</p>
            <div class="col markdown mb-3">
                <ol>
                    <li>Razão social do fornecedor;</li>
                    <li>Velocidade em Mbps(número);</li>
                    <li>Custo de ativação(número);</li>
                    <li>Tecnologia;</li>
                    <li>Adesão do Fornecedor(número);</li>
                    <li>Custo operacional(número);</li>
                    <li>Mensalidade do fornecedor(número);</li>
                    <li>Cobrança mensal com imposto(número);</li>
                    <li>Imposto do Custo de instalação(número);</li>
                    <li>Prazo de instalação do fornecedor;</li>
                    <li>Prazo de instalação;</li>
                    <li>Status(1 - Em aberto, 2 - Fechado);</li>
                </ol>
                <ul>
                    <li>Obs.: Para latitude e longitude será válido valores como 36°23'08.3"S ou -30,046750555651297, atenção para a vírgula ao invés do ponto.</li>
                    <li>Obs.: caso o site já exista no orçamento, os dados serão atualizados conforme estiver na tabela.</li>
                </ul>
                
                <form id="form_{{ $orcamento->id }}" method="post" action="{{ route('site.download.modelo.planilha') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                        Baixe aqui a planilha de modelo
                    </button>
                </form>
            </div>
            <form id="form_create_site_sheet_{{ $orcamento->id }}" method="post" action="{{ route('site.store.sheet', ['orcamento_id' => $orcamento->id]) }}" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="mb-3">
                    {{-- <div class="form-label"></div> --}}
                    <input type="file" name="sites_sheet" id="sites_sheet" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
