<div class="modal modal-blur fade mw-50" id="modal-create-site-sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="modal-title">Adicionar sites via planilha</div>
            <p>Envie um arquivo .xlsx com os seguintes dados em cada coluna:</p>
            <div class="col markdown mb-3">
                <ol>
                    <li>Nome do site;</li>
                    <li>Cidade;</li>
                    <li>UF do estado;</li>
                    <li>Endereço detalhado;</li>
                    <li>Latitude;</li>
                    <li>Longitude.</li>
                </ol>
                <ul>
                    <li>Obs.: Para latitude e longitude será válido valores como 36°23'08.3"S ou -30,046750555651297, atenção para a vírgula ao invés do ponto, altere isso no excel.</li>
                    <li>Obs.: caso o site já exista no orçamento, os dados serão atualizados conforme estiver na planilha.</li>
                    <li>Obs.: o excel só aceita 15 números após a vírgula, caso a latitude ou longitude tiver mais que 15, coloque um ' antes do número, portanto ficará assim: '-30,046750555651297 e dessa forma o excel vai entender como texto e considerar todos os números.</li>
                </ul>
                
                <form id="form_download_planilha" method="post" action="{{ route('siteOrcamento.download.modelo.planilha') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                        Baixe aqui a planilha de modelo
                    </button>
                </form>
            </div>
            <form id="form_create_site_sheet" method="post" action="{{ route('siteOrcamento.store.sheet', ['orcamento' => $orcamento]) }}" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="mb-3">
                    {{-- <div class="form-label"></div> --}}
                    <input type="file" name="sites_sheet" id="sites_sheet" class="form-control" />
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
