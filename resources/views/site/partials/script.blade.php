
<script src="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/js/tom-select.base.min.js" defer></script>
<script>

    $(document).ready(function() {
        $('#gmaps').attr('position', $('#coord').val());
        $('#coord').on('input', function() {
            var coordenadas = $(this).val();

            var separa = coordenadas.includes(', ') ? coordenadas.split(', ') : coordenadas.split(',');
            $('#latitude').val(separa[0]);
            $('#longitude').val(separa[1]);

            if (coordenadas.includes('°') && coordenadas.includes('\'')) {
                var graus = coordenadas.split(', ');
                var lat = convertToDecimal(graus[0]);
                $('#latitude').val(lat);
                var lng = convertToDecimal(graus[1]);
                $('#longitude').val(lng);
                $('#result').html(lat+', '+lng);
                coordenadas = lat+', '+lng;
            } else if (coordenadas.includes('°') && !coordenadas.includes('\'')){
                var separa = coordenadas.includes('°, ') ? coordenadas.split('°, ') : coordenadas.split('°,');
                coordenadas = separa[0]+', '+separa[1].split('°')[0];
                $('#result').html(separa[0]+', '+separa[1].split('°')[0]);
            }

            $('#gmaps').attr('position', coordenadas);
        });
        
        var el;
        
        window.TomSelect && (new TomSelect(el = document.getElementById('servicos'), {
            options: [
                @foreach($servicos as $servico)
                    {value: '{{ $servico->id }}', text: '{{ $servico->nome }}'},
                @endforeach
            ],
            @if(!empty($site))
            items: [
                @foreach($site->servicosSolicitados as $servico)
                    '{{ $servico->id }}',
                @endforeach
            ],
            @endif
        }));
    });

    function convertToDecimal(dms) {
        var graus = dms.includes('° ') ? dms.split('° ') : dms.split('°');
        var resto = graus[1];
        var hora = graus[0];

        var min = resto.includes('\' ') ? resto.split('\' ') : resto.split('\'');
        resto = min[1];
        var minuto = min[0];

        if (resto.includes('\" ')) {
            var seg = resto.split('\" ');
        } else if (resto.includes('\"')) {
            var seg = resto.split('\"');
        } else if (resto.includes('\″ ')) {
            var seg = resto.split('\″ ');
        }  else if (resto.includes('\″')) {
            var seg = resto.split('\″');
        } else if (resto.includes('\"')) {
            var seg = resto.split('\"');
        } else {
            $('#result').html('Insira uma coordenada em valor decimal ou com graus, minutos e segundos. 15°46\'46\"S, 47\°55\'46\"O');
        }
        var letra = seg[1];
        var segs = seg[0];

        var completo = (parseInt(hora) + (parseInt(minuto)/60) + (parseInt(segs)/3600));

        if (letra.includes('S') || letra.includes('W')) {
            completo = -completo;
        }
        
        return completo;
    }
</script>

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiQk-FBvoeK8j7hKpVayMETHx4nuh4fcg&loading=async&libraries=marker&v=beta&solution_channel=GMP_CCS_complexmarkers_v3"
  defer>
</script>