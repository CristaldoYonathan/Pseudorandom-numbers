@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'indexF'])

@section('content')
    <h1 class="text-center">Generador de n&uacute;meros aleatorios</h1>

    <div class="row">
        <div class="col-12">
            <hr>
        </div>
    </div>

    <div class="container">

        <div class="row ">
            <div class="col-6 d-flex flex-column align-items-center">
                <h3 class="text-center">Método de Fibonacci</h3>
                <form action="{{ route('NA.fibonacci') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cantidad">Cantidad de números a generar</label>
                        <input type="number" class="form-control" name="cantidad" id="cantidad" value="100">
                    </div>
                    <div class="form-group">
                        <label for="semillav1">Primer Semilla(V1)</label>
                        <input type="number" class="form-control" name="semillav1" id="semillav1" value="16561">
                    </div>
                    <div class="form-group">
                        <label for="semillav2">Segunda Semilla(V2)</label>
                        <input type="number" class="form-control" name="semillav2" id="semillav2" value="17471">
                    </div>
                    <div class="form-group">
                        <label for="control">Parámetro de control(A)</label>
                        <input type="number" class="form-control" name="control" id="control" value="100411">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3">Generar</button>
                    </div>
                </form>
                @if(isset($numeros))
                    <div class="text-center">
                        <button type="button" class="btn btn-primary mt-3">Probar aleatoriedad</button>
                    </div>
                @endif
            </div>
            <div class="col-6 d-flex flex-column align-items-center">
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
    </div>

@endsection
