@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'tables'])

@section('content')
    <h1>Resultado de fibonacci</h1>
    <h3>Método de Fibonacci</h3>

    <table>
        <thead>
        <tr>
            <th>Iteración</th>
            <th>Valor</th>
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
@endsection


