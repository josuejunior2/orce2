@extends('layouts.admin')

@include('fornecedor.modal.alert-destroy')
@include('fornecedor.modal.update-cidades-sheet')
@include('fornecedor.modal.add-cidade')
@section('content')

    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">{{ $fornecedor->nome }}</h3>
            <div class="d-flex justify-content-between col-auto">
                <div>
                    <a href=" {{ route('fornecedor.edit', ['fornecedor' => $fornecedor->id]) }}"
                        class="btn me-2 btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                        </svg>
                    </a>
                </div>
                @can('excluir fornecedor')
                    <form id="form_{{ $fornecedor->id }}" method="post"
                        action="{{ route('fornecedor.destroy', ['fornecedor' => $fornecedor->id]) }}" class="me-2">
                        @method('DELETE')
                        @csrf
                        <!-- <button type="submit">Excluir</button>  -->
                        <a href="#" class="btn btn-danger w-100" data-bs-toggle="modal"
                            data-bs-target="#modal-destroy-fornecedor">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a>
                    </form>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Email</div>
                    <div class="datagrid-content">{{ $fornecedor->email }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Telefone</div>
                    <div class="datagrid-content">{{ $fornecedor->telefone }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Nome do representante</div>
                    <div class="datagrid-content">{{ $fornecedor->representante }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Lista de Cotações</h3>
        </div>
        <div class="card-body">
            @if ($fornecedor->cotacoes->isEmpty())
                <div class="form-control-plaintext m-3">Esse fornecedor não participa em nenhuma cotação. <a
                        href="{{ route('cotacao.index') }}">Selecione um orçamento e faça a cotação de um site.</a></div>
            @elseif ($fornecedor->cotacoes->isNotEmpty())
                <div class="accordion" id="accordion">
                    @foreach ($fornecedor->cotacoes as $cotacao)
                        <div class="accordion-item m-3">
                            <div class="d-flex justify-content-between" id="heading-1">
                                <button class="accordion-header " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accordion-collapse-{{ $cotacao->id }}" aria-expanded="true">
                                    {{ $cotacao->SiteOrcamento->Site->nome }}
                                </button>
                                <div class="d-flex justify-content-between col-auto">
                                    <a href="{{ route('orcamento.show', ['orcamento' => $cotacao->SiteOrcamento->Orcamento]) }}"
                                        class="btn me-2 btn-secondary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div id="accordion-collapse-{{ $cotacao->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordion-{{ $cotacao->id }}">
                                <div class="accordion-body pt-0 mt-3">
                                    <div class="datagrid">
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Endereço</div>
                                            <div class="datagrid-content">{{ $cotacao->SiteOrcamento->Site->endereco }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Cidade</div>
                                            <div class="datagrid-content">{{ $cotacao->SiteOrcamento->Site->Cidade->nome }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Estado</div>
                                            <div class="datagrid-content">{{ $cotacao->SiteOrcamento->Site->Cidade->Estado->nome }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Velocidade</div>
                                            <div class="datagrid-content">{{ $cotacao->vel_down }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Tempo do contrato</div>
                                            <div class="datagrid-content">{{ $cotacao->SiteOrcamento->Orcamento->tempo_contrato }}
                                            </div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Tecnologia</div>
                                            <div class="datagrid-content">{{ App\Models\Cotacao::getTecnologiaTexto($cotacao->tecnologia) }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Fornecedor</div>
                                            <div class="datagrid-content">{{ $cotacao->Fornecedor->nome }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Prazo de instalação do Fornecedor</div>
                                            <div class="datagrid-content">{{ $cotacao->prazo_instalacao_fornecedor }}
                                            </div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Adesão do Fornecedor</div>
                                            <div class="datagrid-content">{{ $cotacao->adesao_fornecedor }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Mensalidade do Fornecedor</div>
                                            <div class="datagrid-content">{{ $cotacao->mensal_fornecedor }}</div>
                                        </div>
                                        @can('precificar orcamento')
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Mensalidade c/ imposto</div>
                                                <div class="datagrid-content"><strong>{{ $cotacao->mensal_imp }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Custo de instalação c/ imposto</div>
                                                <div class="datagrid-content">
                                                    <strong>{{ $cotacao->custo_instalacao_imp }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Imposto mensal</div>
                                                <div class="datagrid-content"><strong>{{ $cotacao->imposto_mensal }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Imposto da adesão</div>
                                                <div class="datagrid-content"><strong>{{ $cotacao->imposto_adesao }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Lucro na Adesão</div>
                                                <div class="datagrid-content"><strong>{{ $cotacao->lucro_adesao }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Lucro Líquido</div>
                                                <div class="datagrid-content"><strong>{{ $cotacao->lucro_liquido }}</strong>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Prazo de instalação</div>
                                                <div class="datagrid-content">{{ $cotacao->prazo_instalacao }}</div>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-12">
        <div class="card m-3">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Lista de cidades onde o fornecedor atende</h3>
                <div class="d-flex justify-content-between col-auto">
                    <div class="me-2">
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#modal-update-cidades-sheet">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-table-import">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 21h-7a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8" />
                                <path d="M3 10h18" />
                                <path d="M10 3v18" />
                                <path d="M19 22v-6" />
                                <path d="M22 19l-3 -3l-3 3" />
                            </svg>
                            Vincular cidades via planilha
                        </a>
                    </div>
                    <div>
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#modal-add-cidade">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Vincular cidade
                        </a>
                    </div>
                </div>
            </div>
            @if (isset($fornecedor->cidades))
                <div class="table-responsive m-4">
                    <table class="display w-100" id="tabela-cidades-fornecedor"> {{-- table card-table table-vcenter text-nowrap datatable --}}
                        <thead>
                            <tr>
                                {{-- <th class="w-1"></th>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"> --}}
                                <th class="w-1">ID <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 15l6 -6l6 6" />
                                    </svg>
                                </th>
                                <th>Nome</th>
                                <th>Estado</th>
                                <th>Cotações fechadas</th>
                                <th>Cotações em aberto</th>
                                <th></th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fornecedor->cidades as $c)
                                @include('fornecedor.modal.alert-detach', ['cidade' => $c])
                                <tr>
                                    <!--<td></td>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"> -->
                                    <td><span class="text-muted">{{ $c->id }}</span></td>
                                    <td>{{ $c->nome }}</td>
                                    <td>{{ $c->Estado->nome }}</td>
                                    <td>{{ $fornecedor->cotacoes->where('site.cidade_id', $c->id)->where('status', 'Fechado')->count() }}
                                    </td>
                                    <td>{{ $fornecedor->cotacoes->where('site.cidade_id', $c->id)->where('status', 'Em aberto')->count() }}
                                    </td>
                                    <td class="d-flex align-items-center justify-content-center text-end">
                                        @if ($fornecedor->cotacoes->where('cidade_id', $c->id)->count() == 0)
                                            <form id="form_detach_cidade_{{ $c->id }}" method="post"
                                                action="{{ route('fornecedor.detach.cidade') }}" class="me-2">
                                                @csrf
                                                <input type="hidden" name="fornecedor_id" id="fornecedor_id"
                                                    value="{{ $c->pivot->fornecedor_id }}">
                                                <input type="hidden" name="cidade_id" id="cidade_id"
                                                    value="{{ $c->pivot->cidade_id }}">
                                                <a href="#" class="btn btn-outline-danger w-45"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-detach-cidade-{{ $c->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-unlink">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M17 22v-2" />
                                                        <path d="M9 15l6 -6" />
                                                        <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                        <path
                                                            d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                        <path d="M20 17h2" />
                                                        <path d="M2 7h2" />
                                                        <path d="M7 2v2" />
                                                    </svg>
                                                    Desvincular
                                                </a>
                                            </form>
                                        @endif
                                    </td>
                                    {{-- <td class="text-end">
                    <span class="dropdown">
                      <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Ações</button>
                      <div class="dropdown-menu dropdown-menu-end">
                          <a class="dropdown-item" href="{{ route('cidade.show', ['cidade' => $c]) }}">
                              Visualizar
                          </a>
                      </div>
                    </span>
                  </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    @else
        <div class="form-control-plaintext m-3">Esse fornecedor não está vinculado a nenhuma cidade.</div>
        @endif
    </div>

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabela-cidades-fornecedor').DataTable({
                "paging": true,
                "ordering": true,
                "searching": true,
                "pageLength": 10,
                "language": {
                    url: '/pt-br-datatables.json',
                },
            });
        });
    </script>
@endsection

@endsection
