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

            <form id="form" class="needs-validation mt-4" action="{{ route('NA.congruencias') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cantidadCong">Cantidad de números a generar:</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="cantidadCong" id="cantidadCong" value="10" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="semillavi">Semilla(V<sub>i</sub>):</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="semillavi" id="semillavi" value="300" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="semiillavik">Segunda semilla(V<sub>i-k</sub>):</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="semiillavik" id="semiillavik" value="1000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="constanteA">Primer Constante(a):</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="constanteA" id="constanteA" value="400" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="constanteC">Segunda Constante(c):</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="constanteC" id="constanteC" value="1000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="constanteM">Cuarta Constante(m):</label>
                    <div class="form-group">
                        <input type="number" class="form-control" name="constanteM" id="constanteM" value="1000" required>
                    </div>
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

        <div class="col-6" style="max-height: 550px; overflow: auto;">
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

@push('js')
    <script>
        // validar los campos según las reglas
        $(document).ready(function () {

            // validación para campo cantidadCong
            $('#cantidadCong').on('input change', function () {
                if ($(this).val() < 10 || $(this).val() % 1 != 0) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });

            // validación para campo semillavi
            $('#semillavi').on('input change', function () {
                $('#constanteM').trigger('change');
                if ($(this).val() < 0 || $(this).val() % 1 != 0) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });


            // validación para campo semiillavik
            $('#semiillavik').on('input change', function () {
                if ($(this).val() < 0 || $(this).val() % 1 != 0) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });

            // validación para campo constanteA
            $('#constanteA').on('input change', function () {
                $('#constanteM').trigger('change');
                if ($(this).val() < 0 || $(this).val() % 1 != 0) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });

            // validación para campo constanteC
            $('#constanteC').on('input change', function () {
                if ($(this).val() < 0 || $(this).val() % 1 != 0) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });

            // validación para campo constanteM
            $('#constanteM').on('input change', function () {
                const constanteM = parseFloat($(this).val());
                const constanteA = parseFloat($('#constanteA').val());
                const semillavi = parseFloat($('#semillavi').val());

                console.log(constanteM, constanteA, semillavi);

                if (constanteM <= 0 || constanteM % 1 !== 0 || constanteM < constanteA || constanteM < semillavi) {
                    $(this).parent().removeClass('has-success');
                    $(this).parent().addClass('has-danger');
                } else {
                    $(this).parent().removeClass('has-danger');
                    $(this).parent().addClass('has-success');
                }
            });


            // Alerta de validación de campos
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
                        text: "Hay campos que no cumplen con las reglas de la formula de congruencia fundamental.\nLa formula es la siguiente:\nXn+1 = (aXn + c) mod m\nDonde:\nXn+1 = Semilla\na = Constante multiplicativa\nXn = Semilla anterior\nC = Constante aditiva\nm = Constante de modulo\n\nPor favor, verifique los campos.",
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
