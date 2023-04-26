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

                <form id="form" action="{{ route('NA.fibonacci') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cantidad">Cantidad de números a generar</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="cantidad" id="cantidad" value="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="semillav1">Primer Semilla(V1)</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="semillav1" id="semillav1" value="300">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="semillav2">Segunda Semilla(V2)</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="semillav2" id="semillav2" value="400">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="control">Parámetro de control(A)</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="control" id="control" value="1000">
                        </div>
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
            <div class="col-6 d-flex flex-column align-items-center" style="max-height: 400px; overflow: auto;">
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

@push('js')
    <script>
        // validar los campos según la las reglas de la formula de fibonacci
        $(document).ready(function () {


            $('#cantidad').on('input change', function () {
                // cambiar la clase de los inputs
                if ($(this).val() < 10) {
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
                if ($(this).val() < 100 || $(this).val() > 999999) {
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
                if ($(this).val() < 100 || $(this).val() > 999999) {
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
                if ($(this).val() < 100 || $(this).val() > 999999) {
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
