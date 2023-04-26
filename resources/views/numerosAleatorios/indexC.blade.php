@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'indexC'])

@section('content')
    <h1 class="text-center">Generador de n&uacute;meros aleatorios</h1>

{{--    linea de separación--}}
    <div class="row">
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-6 d-flex flex-column align-items-center">
            <h3 class="text-center" >Método de Congruencia Fundamental</h3>

            <p class="text-center h4">
                La formula a utilizar para llevar a cabo la generación de números aleatorios es la siguiente:<br><br>
                <strong>V<sub>i+1</sub> = (a*V<sub>i</sub> + c*V<sub>i-k</sub>) mod m</strong>
            </p>

            <form class="needs-validation mt-4" action="{{ route('NA.congruencias') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cantidadCong">Cantidad de números a generar:</label>
                    <input type="number" class="form-control" name="cantidadCong" id="cantidadCong" value="100" required>
                </div>
                <div class="form-group">
                    <label for="semillavi">Semilla(V<sub>i</sub>):</label>
                    <input type="number" class="form-control" name="semillavi" id="semillavi" value="16561" required>
                </div>
                <div class="form-group">
                    <label for="semiillavik">Segunda semilla(V<sub>i-k</sub>):</label>
                    <input type="number" class="form-control" name="semiillavik" id="semiillavik" value="17471" required>
                </div>
                <div class="form-group">
                    <label for="constanteA">Primer Constante(a):</label>
                    <input type="number" class="form-control" name="constanteA" id="constanteA" value="16661" required>
                </div>
                <div class="form-group">
                    <label for="constanteC">Segunda Constante(c):</label>
                    <input type="number" class="form-control" name="constanteC" id="constanteC" value="17971" required>
                </div>
                <div class="form-group">
                    <label for="constanteM">Cuarta Constante(m):</label>
                    <input type="number" class="form-control" name="constanteM" id="constanteM" value="18181" required>
                </div>
                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">Generar</button>
                </div>
            </form>
            @if(isset($numeros))
                <div class="text-center">
                    <button type="button" class="btn btn-primary mt-3">Probar aleatoriedad</button>
                </div>
            @endif
        </div>

        <div class="col-6">
            <h3 class="text-center" >Resultados de la generación</h3>
            @if(isset($numeros))
                <table class="table tablesorter">
                    <thead>
                    <tr>
                        <th >Iteración</th>
                        <th >Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($numeros as $numero)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $numero}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>


@endsection
