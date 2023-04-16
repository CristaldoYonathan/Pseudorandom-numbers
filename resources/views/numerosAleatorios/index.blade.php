@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'tables'])

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

    <h3>Método de Congruencia Fundamental</h3>

    <form action="{{ route('NA.congruencias') }}" method="POST">
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
@endsection
