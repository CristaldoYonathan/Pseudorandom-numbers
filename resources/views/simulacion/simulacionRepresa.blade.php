@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'indexSimulacion'])

@section('content')
    <h1 class="text-center">Simulación de Represa Hidroeléctrica</h1>

    <div class="row">
        <div class="col-12">
            <hr>
        </div>
    </div>

    <div class="container">

        @if(isset($seriesF,$seriesC))
            <h3 class="text-center">Seleccione la serie que quieras simular</h3>
            <div class="row">

                <div class="col-6 d-flex flex-column align-items-center">

                    <form action="{{route('S.Simulacion')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Fibonacci</option>
                            @foreach($seriesF as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseF}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="metodo" value="fibonacci">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar Simulacion para fibonacci</button>
                        </div>
                    </form>
                </div>
                <div class="col-6 d-flex flex-column align-items-center">

                    <form action="{{route('S.Simulacion')}}" method="GET">
                        <select class="form-control" id="serie" name="serie">
                            <option value="0">Seleccione una serie del metodo Congruencia</option>
                            @foreach($seriesC as $serie)
                                <option value="{{$serie->id}}">{{$serie->valoresBaseC}}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="metodo" value="congruencias">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">Generar Simulacion para congruencia</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if(isset($caudal))

                <h3 class="text-center">Resumen de información sobre el caudal</h3>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">En el año se obtuvieron los siguientes datos:</p>
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-light">El <b>caudal inicial</b> fue de {{$caudalInicial}} m<sup>3</sup>/s</li>
                            <li class="list-group-item list-group-item-light">El <b>caudal promedio</b> fue de {{$infocaudal['promedio']}} m<sup>3</sup>/s</li>
                            <li class="list-group-item list-group-item-light">El <b>caudal máximo</b> fue de {{$infocaudal['maximo']}} m<sup>3</sup>/s</li>
                            <li class="list-group-item list-group-item-light">El <b>caudal mínimo</b> fue de {{$infocaudal['minimo']}} m<sup>3</sup>/s</li>
                            <li class="list-group-item list-group-item-light">La <b>primera compuerta</b> se abrió {{$infocompuertas['primera']}} veces</li>
                            <li class="list-group-item list-group-item-light">La <b>segunda compuerta</b> se abrió {{$infocompuertas['segunda']}} veces</li>
                            <li class="list-group-item list-group-item-light">La <b>tercera compuerta</b> se abrió {{$infocompuertas['tercera']}} veces</li>
                            <li class="list-group-item list-group-item-light">La <b>cuarta compuerta</b> se abrió {{$infocompuertas['cuarta']}} veces</li>
                            <li class="list-group-item list-group-item-light">La <b>alerta roja</b> se activó {{$infoalerta['activacion']}} veces</li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <h3 class="text-center">Tabla de caudal anual</h3>

            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th scope="col">Dia</th>
                            <th scope="col">Valor generado</th>
                            <th scope="col">Caudal sin desborde <b>(m<sup>3</sup>/s)</b></th>
                            <th scope="col">Caudal <b>(m<sup>3</sup>/s)</b></th>
                            <th scope="col">Compuertas abiertas</th>
                            <th scope="col">Energía generada <b>(Mw)</b></th>
                            <th scope="col">Energía perdida <b>(Mw)</b></th>
                            <th scope="col">Activación de alerta</th>
                            <th scope="col">Represa rota</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($caudales as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$normalizedArray[$key]}}</td>
                                <td>{{$caudalesSinDesborde[$key]}}</td>
                                <td>{{$value}}</td>
                                <td>
                                    @if($caudalesSinDesborde[$key] < 15000)
                                        <span class="text-success">No se abrieron compuertas</span>
                                    @elseif($caudalesSinDesborde[$key] >= 15000 && $caudalesSinDesborde[$key] < 25000)
                                        <span class="text-info">Se abrió la primera compuerta</span>
                                    @elseif($caudalesSinDesborde[$key] >= 25000 && $caudalesSinDesborde[$key] < 32000)
                                        <span class="text-warning">Se abrió la primera y segunda compuerta</span>
                                    @elseif($caudalesSinDesborde[$key] >= 32000 && $caudalesSinDesborde[$key] < 40000)
                                        <span class="text-warning">Se abrió la primera, segunda y tercera compuerta</span>
                                    @elseif($caudalesSinDesborde[$key] >= 40000)
                                        <span class="text-danger">Se abrió la primera, segunda, tercera y cuarta compuerta</span>
                                    @endif
                                </td>
                                <td>{{$energiaGenerada[$key]}}</td>
                                <td>{{$energiaPerdida[$key]}}</td>
                                <td>
                                    @if($caudalesSinDesborde[$key] <= 45000)
                                        <span class="text-success">No se activó la alerta roja</span>
                                    @else
                                        <span class="text-danger">Se activó la alerta roja</span>
                                    @endif
                                </td>
                                <td>
                                    @if($caudalesSinDesborde[$key] <= 52000)
                                        <span class="text-success">No se rompió la represa</span>
                                    @else
                                        <span class="text-danger">Se rompió la represa</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endif
    </div>

@endsection

@push('js')
@endpush
