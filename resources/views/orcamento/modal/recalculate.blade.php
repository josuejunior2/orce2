<div class="modal modal-blur fade mw-50" id="modal-recalculate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">
                    Recalcular todas as cotações
                </div>
                <div class="card-table table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th colspan="2">Valores do orçamento</th>
                            </tr>
                            <tr>
                                <th>% Custo fixo</th>
                                <th>GearNoc</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>R$ {{ number_format($orcamento->custo_fixo_percent, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($orcamento->gear_noc, 2, ',', '.') }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
                <form id="form_recalculate_orcamento" method="post" action="{{ route('orcamento.recalculate', ['orcamento' => $orcamento]) }}" novalidate>
                    @method('POST')
                    @csrf
                    <div class="row g-3 mb-4 justify-content-center">
                        <div class="col-md">
                            <label for="custo_fixo_percent" class="col-form-label form-label">Custo fixo</label>
                            <div class="input-group">
                                <input id="custo_fixo_percent" name="custo_fixo_percent" type="number" class="form-control" value="{{ old('custo_fixo_percent', auth()->user()->Empresa->custo_fixo_percent) }}" />
                                <span class="input-group-text">
                                    %
                                </span>
                                <span class="{{ $errors->has('custo_fixo_percent') ? 'text-danger' : '' }}">
                                    {{ $errors->has('custo_fixo_percent') ? $errors->first('custo_fixo_percent') : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md">
                            <label for="gear_noc" class="col-form-label form-label">GearNoc</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    R$
                                </span>
                                <input id="gear_noc" name="gear_noc" type="number" class="form-control" value="{{ old('gear_noc', auth()->user()->Empresa->gear_noc) }}" />
                                <span class="{{ $errors->has('gear_noc') ? 'text-danger' : '' }}">
                                    {{ $errors->has('gear_noc') ? $errors->first('gear_noc') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" onclick="document.getElementById('form_recalculate_orcamento').submit()">Enviar</button>
                </div>
                </form>
        </div>
    </div>
</div>
