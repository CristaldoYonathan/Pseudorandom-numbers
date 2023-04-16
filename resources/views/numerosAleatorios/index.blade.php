@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'index'])

@section('content')
    <h1>Generador de n&uacute;meros aleatorios</h1>
    <h3>Método de Fibonacci</h3>

    <form action="{{ route('NA.fibonacci') }}" method="POST">
        @csrf
        <label for="cantidad">Cantidad de n&uacute;meros a generar</label>
        <input type="number" name="cantidad" id="cantidad" value="10">
        <br>
        <label for="semillav1">Primer Semilla(V1)</label>
        <input type="number" name="semillav1" id="semillav1" value="300">
        <br>
        <label for="semillav2">Segunda Semilla(V2)</label>
        <input type="number" name="semillav2" id="semillav2" value="400">
        <br>
        <label for="control">Parámetro de control(A)</label>
        <input type="number" name="control" id="control" value="1000">
        <br>
        <button type="submit">Generar</button>
    </form>

    <hr style="height: 5px; background: #1a202c">

    <div class="row">
        <div class="col-6">
            <h3 class="text-center" >Método de Congruencia Fundamental</h3>

            <p class="text-center h4">
                La formula a utilizar para llevar a cabo la generación de números aleatorios es la siguiente:<br><br>
                <strong>V<sub>i+1</sub> = (a*V<sub>i</sub> + c*V<sub>i-k</sub>) mod m</strong>

            </p>

            <form class="needs-validation mt-4" action="{{ route('NA.congruencias') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cantidadCong">Cantidad de números a generar:</label>
                    <input type="number" class="form-control w-50" name="cantidadCong" id="cantidadCong" value="10" required>
                </div>
                <div class="form-group">
                    <label for="semillavi">Semilla(V<sub>i</sub>):</label>
                    <input type="number" class="form-control w-50" name="semillavi" id="semillavi" value="300" required>
                </div>
                <div class="form-group">
                    <label for="semiillavik">Segunda semilla(V<sub>i-k</sub>):</label>
                    <input type="number" class="form-control w-50" name="semiillavik" id="semiillavik" value="1000" required>
                </div>
                <div class="form-group">
                    <label for="constanteA">Primer Constante(a):</label>
                    <input type="number" class="form-control w-50" name="constanteA" id="constanteA" value="400" required>
                </div>
                <div class="form-group">
                    <label for="constanteC">Segunda Constante(c):</label>
                    <input type="number" class="form-control w-50" name="constanteC" id="constanteC" value="1000" required>
                </div>
                <div class="form-group">
                    <label for="constanteM">Cuarta Constante(m):</label>
                    <input type="number" class="form-control w-50" name="constanteM" id="constanteM" value="1000" required>
                </div>
                <button type="submit" class="btn btn-primary">Generar</button>
            </form>
        </div>
        <div class="col-6">
            <h3 class="text-center" >Resultados de la generación</h3>
        </div>
    </div>


@endsection
