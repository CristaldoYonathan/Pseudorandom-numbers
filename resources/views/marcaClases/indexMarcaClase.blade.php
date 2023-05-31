@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'indexClases'])

@section('content')
    <h1 class="text-center">Método de Marca de Clases</h1>

    <div class="row">
        <div class="col-12">
            <hr>
        </div>
    </div>

    <div class="container">

        @if(isset($seriesF,$seriesC))
            <h3 class="text-center">Seleccione la serie que quiera poner a prueba</h3>
            <div class="row">

                <div class="col-6 d-flex flex-column align-items-center">

                    <form action="{{route('MC.MarcaClases')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Fibonacci</option>
                            @foreach($seriesF as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseF}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="metodo" value="fibonacci">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar Marca de clase para fibonacci</button>
                        </div>
                    </form>
                </div>
                <div class="col-6 d-flex flex-column align-items-center">

                    <form action="{{route('MC.MarcaClases')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Congruencia</option>
                            @foreach($seriesC as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseC}}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="metodo" value="congruencias">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar Marca de clase para congruencia</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

            @if(isset($Fx) && isset($probabilidad_ocurrencia))
                <canvas id="lineChart"></canvas>
            @endif

        {{--@if(isset($resultado))

                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <a href="{{route('TA.indexPoker')}}" class="btn btn-primary mt-3">Probar otra serie</a>
                        </div>
                    </div>
                    <div class="col-6">
--}}{{--                        form de tipo post para enviar estos datos $fo,$fe,$chi_cuadrado_calculado,$resultado,$metodo,$id_serie a la ruta TA.storeChi--}}{{--
                        <form action="{{route('TA.storePoker')}}" method="POST">
                            @csrf
                            <input type="hidden" name="fo" value="{{json_encode($fo)}}">
                            <input type="hidden" name="fe" value="{{json_encode($fe)}}">
                            <input type="hidden" name="chi_cuadrado_calculado" value="{{$chi_cuadrado_calculado}}">
                            <input type="hidden" name="chi_cuadrado_limite" value="{{$chi_cuadrado_limite}}">
                            <input type="hidden" name="grados_libertad" value="{{$grados_de_libertad}}">
                            <input type="hidden" name="resultado" value="{{$resultado}}">
                            <input type="hidden" name="metodo" value="{{$metodo}}">
                            <input type="hidden" name="id_serie" value="{{$id_serie}}">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-3">Guardar resultado de test</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="mb-5">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="mt-3 text-center">Hipotesis planteadas</h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="text-center">Hipotesis nula (H0)</h4>
                                        <p class="text-center">Los números generados por el método <b>{{$metodo}} son aleatorios</b></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-center">Hipotesis alternativa (H1)</h4>
                                        <p class="text-center">Los números generados por el método <b>{{$metodo}} no son aleatorios</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center mt-3">Resultados del test usando serie de <b>{{$metodo}}</b></h3>
                                <table class="table tablesorter text-center">
                                    <thead>
                                    <tr>
                                        <th >Nivel de significancia</th>
                                        <th >Grados de libertad</th>
                                        <th >Valor de Chi Cuadrado calculado</th>
                                        <th >Valor de Chi Cuadrado limite</th>
                                        <th >Resultado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$significancia}}</td>
                                        <td>{{$grados_de_libertad}}</td>
                                        <td>{{ $chi_cuadrado_calculado }}</td>
                                        <td>{{ $chi_cuadrado_limite }}</td>
                                        <td>
                                            @if($resultado)
                                                <span class="badge badge-success">Aceptado</span>
                                            @else
                                                <span class="badge badge-danger">Rechazado</span>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr class="mb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="mt-3 text-center">Conclusion del test</h3>
                            </div>
                            <div class="col-md-12 h4 text-center">
                                Teniendo en cuenta los datos presentados y calculos realizado podemos concluir que
                                @if($chi_cuadrado_calculado > $chi_cuadrado_limite)
                                    los números generados por el método <b>{{$metodo}} no son aleatorios</b>, y se debe <b>rechazar la hipótesis nula (H0)</b> y aceptar la hipótesis alternativa (H1).
                                @else
                                    los números generados por el método <b>{{$metodo}} son aleatorios</b>, y se debe <b>aceptar la hipótesis nula (H0)</b> y rechazar la hipótesis alternativa (H1).
                                @endif
                            </div>
                        </div>
                        <hr class="mb-5">
                        <div class="row">
                            <div class="col-md-12" style="max-height: 400px; overflow: auto;">
                                <h3 class="text-center mt-3">Valores generados por el metodo</h3>
                                <table class="table tablesorter">
                                    <thead>
                                    <tr>
                                        <th >Iteración</th>
                                        <th >Valor</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($serie as $series)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $series}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif--}}
    </div>

@endsection

@push('js')
    <script>
        // validar los campos según la las reglas de la formula de fibonacci
        $(document).ready(function () {


            $('#cantidad').on('input change', function () {
                // cambiar la clase de los inputs
                if ($(this).val() < 10 || $(this).val() === '') {
                    // el padre agrege la clase has-danger
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                }else{
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }

            });
            $('#semillav1').on('input change', function () {
                // cambiar la clase de los inputs
                if ($(this).val() < 100 || $(this).val() > 999999 || $(this).val() === '') {
                    // el padre agrege la clase has-danger
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                }else{
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });
            $('#semillav2').on('input change', function () {
                // cambiar la clase de los inputs
                if ($(this).val() < 100 || $(this).val() > 999999 || $(this).val() === '') {
                    // el padre agrege la clase has-danger
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                }else{
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });
            $('#control').on('input change', function () {
                // cambiar la clase de los inputs
                if ($(this).val() < 100 || $(this).val() > 999999 || $(this).val() === '') {
                    // el padre agrege la clase has-danger
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                }else{
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });

            // si hay una clase has-danger en el padre del input entonces no se puede enviar el formulario
            $('#form').on('submit', function (e) {
                // crear un foreach para recorrer todos los padres de los inputs y verificar si tienen la clase has-danger
                var error = false;
                $(this).find('.form-group').each(function () {
                    if ($(this).hasClass('has-danger')) {
                        error = true;
                    }
                });
                if (error) {
                    e.preventDefault();
                    swal({
                        title: "Error",
                        text: "Hay campos que no cumplen con las reglas de la formula de fibonacci",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-danger",
                        type: "error",
                        dangerMode: true,
                    });
                }else{
                    $(this).submit();
                }
            });
        });


    </script>

    @if(isset($Fx) && isset($probabilidad_ocurrencia))
        <script>
            // Datos
            let Probabilidad_ocurrencia = @json($probabilidad_ocurrencia);
            let Fx = @json($Fx);

            // Configuración de la gráfica
            let ctx = document.getElementById('lineChart').getContext('2d');
            let lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['1', '2', '3', '4', '5', '6'],
                    datasets: [
                        {
                            label: 'Probabilidad de ocurrencia',
                            data: Probabilidad_ocurrencia,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.3)',
                            fill: true
                        },
                        {
                            label: 'Probabilidad esperada',
                            data: Fx,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.3)',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Gráfica de Línea'
                    }
                }
            });
        </script>
    @endif
@endpush
