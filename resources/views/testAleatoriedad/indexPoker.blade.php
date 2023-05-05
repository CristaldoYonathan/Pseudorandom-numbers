@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'indexPoker'])

@section('content')
    <h1 class="text-center">Test de aleatoriedad: <b>Poker</b></h1>

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

                    <form action="{{route('TA.Poker')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Fibonacci</option>
                            @foreach($seriesF as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseF}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="metodo" value="fibonacci">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar test fibonacci</button>
                        </div>
                    </form>
                </div>
                <div class="col-6 d-flex flex-column align-items-center">

                    <form action="{{route('TA.Poker')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Congruencia</option>
                            @foreach($seriesC as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseC}}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="metodo" value="congruencias">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar test congruencia</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if(isset($resultado))

                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <a href="{{route('TA.indexChi')}}" class="btn btn-primary mt-3">Probar otra serie</a>
                        </div>
                    </div>
                    <div class="col-6">
{{--                        form de tipo post para enviar estos datos $fo,$fe,$chi_cuadrado_calculado,$resultado,$metodo,$id_serie a la ruta TA.storeChi--}}
                        <form action="{{route('TA.storeChi')}}" method="POST">
                            @csrf
                            <input type="hidden" name="fo" value="{{json_encode($fo)}}">
                            <input type="hidden" name="fe" value="{{$fe}}">
                            <input type="hidden" name="chi_cuadrado_calculado" value="{{$chi_cuadrado_calculado}}">
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
                                        <td>9</td>
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
            @endif
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
@endpush
