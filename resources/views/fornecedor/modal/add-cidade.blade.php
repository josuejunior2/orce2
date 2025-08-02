<div class="modal modal-blur fade" id="modal-add-cidade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="modal-title">Vincular cidade</div>
            <p>Coloque o nome exato da cidade. Em caso de erro, confira a <a href="{{ route('cidade.index') }}">tabela de cidades</a>.</p>
            <form id="form_add_cidade_{{ $fornecedor->id }}" method="post" action="{{ route('fornecedor.attach.cidade', ['fornecedor' => $fornecedor]) }}" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="col-md mb-1">
                    <div class="form-label required">Nome da cidade</div>
                    <input type="text" class="form-control" name="nome" id="nome">
                </div>
                <div class="col-md">
                    <div class="form-label required">Estado</div>
                    <select class="form-select" name="estado" id="estado">
                        <option value=""> -- Selecione o estado -- </option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                    <span class="{{ $errors->has('estado') ? 'text-danger' : '' }}">
                            {{ $errors->has('estado') ? $errors->first('estado') : '' }}
                        </span>
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
