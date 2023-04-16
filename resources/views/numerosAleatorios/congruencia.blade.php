{{--haz una pagina SPA--}}
@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'tables'])

@section('content')
{{--    en esta pagina se verá solo el metodo de congruencia--}}

    <h1 class="text-center">Generador de números aleatorios por el método de congruencia</h1>



<div class="col-md-12">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title ">Método de Congruencia Fundamental</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class=" text-primary">
                        <th>Iteración</th>
                        <th>Valor</th>
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
            </div>
        </div>
    </div>
</div>
@endsection

