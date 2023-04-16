<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultado de fibonacci</title>
</head>
<body>

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

</body>
</html>

