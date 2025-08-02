<div class="card m-3">
    <div class="card-header justify-content-between p-1">
        <h3 class="card-title">Adicione o número de colunas</h3>
        <div>
            <button type="button" class="btn btn-danger" id="remove-col">
                <svg  xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18" viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg>
            </button>
            <button type="button" class="btn btn-info" id="add-col">
                <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 5l0 14" />
                    <path d="M5 12l14 0" />
                </svg>
            </button>
        </div>
    </div>
    <div class="card-body row row-cols">
        <form id="form_create_site_sheet" method="post" action="{{ route('site.store.sheet', ['orcamento' => $orcamento]) }}" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr class="tr-head">

                    </tr>
                </thead>
                <tbody>
                    <tr class="tr-body">

                    </tr>
                </tbody>
            </table>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div class="">
            <input type="file" name="sites_sheet" id="sites_sheet" class="form-control" />
        </div>
        <button type="submit" class="btn btn-success">Enviar</button>
    </div>
        </form>
</div>

@section('js-add')
<script type="text/template" id="td-atributo-template">
    <td>
        <select name="colunas[#]" id="colunas#" class="form-select">
            @foreach($colunas as $coluna)
                <option value="{{$coluna}}">{{ $coluna }}</option>
            @endforeach
        </select>
    </td>
</script>
<script>
    $(document).ready(function() {
        qtd_cols = 0;
        var qtdColunas = "{{ count($colunas) }}";
        var colunasABC = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $('#add-col').on('click', function() {
            if(qtd_cols == qtdColunas){
                return;
            }
            var template = $($('#td-atributo-template').html().replaceAll('#', qtd_cols));
            $('.tr-body').append(template);
            $('.tr-head').append(`<th>${colunasABC.charAt(qtd_cols)}</th>`);
            qtd_cols++;
            $(".somar").show();
        });
        $('#remove-col').on('click', function() {
            if(qtd_cols == 1){
                return;
            }
            $('.tr-body').find('td').last().remove();
            $('.tr-head').find('th').last().remove();
            qtd_cols--
        });
    });
</script>

@endsection

