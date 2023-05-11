<?php

namespace App\Http\Controllers;

use App\Models\NumerosCongruencia;
use App\Models\NumerosFibonacci;
use App\Models\Resultadochi;
use App\Models\Resultadopoker;
use Illuminate\Http\Request;

class TestAleatoriedadController extends Controller
{

    public function indexChi()
    {

        $seriesF = NumerosFibonacci::get()->where('user_id', auth()->user()->id);
        $seriesC = NumerosCongruencia::get()->where('user_id', auth()->user()->id);

        return view('testAleatoriedad.indexChi', compact('seriesF','seriesC'));
    }

    public function indexPoker()
    {

        $seriesF = NumerosFibonacci::get()->where('user_id', auth()->user()->id);
        $seriesC = NumerosCongruencia::get()->where('user_id', auth()->user()->id);

        return view('testAleatoriedad.indexPoker', compact('seriesF','seriesC'));
    }

    public function chiCuadrado(Request $request){

        $id_serie = $request->input('serie');
        $significancia = 0.01;
        if ($request->input('metodo') == 'fibonacci'){
            $valoresObtenidos = NumerosFibonacci::get()->where('user_id', auth()->user()->id)->where('id', $request->input('serie'))->first();
            $serie = json_decode($valoresObtenidos->valoresGeneradosF);
            $iniciales = json_decode($valoresObtenidos->valoresBaseF);
            $metodo = 'fibonacci';
        }else if ($request->input('metodo') == 'congruencias'){
            $valoresObtenidos = NumerosCongruencia::get()->where('user_id', auth()->user()->id)->where('id', $request->input('serie'))->first();
            $serie = json_decode($valoresObtenidos->valoresGeneradosC);
            $iniciales = json_decode($valoresObtenidos->valoresBaseC);
            $metodo = 'congruencias';
        }

        $numerosIndividuales = [];

        $fo = array_fill(0, 10, 0); // Inicializar con 0 en todas las claves del 0 al 9

        foreach ($serie as $numero) {
            $cadena = strval($numero);
            foreach (str_split($cadena) as $digito) {
                $valor = intval($digito);
                $numerosIndividuales[] = $valor;
                $fo[$valor]++;
            }
        }

        $fe = array_sum($fo)*(1/10);

        $chi_cuadrado_calculado = 0;
        foreach ($fo as $valor) {
            $chi_cuadrado_calculado += pow($valor - $fe, 2) / $fe;
        }
        $chi_cuadrado_limite = 21.6660;

        //verificar cual es mayor si el chi cuadrado calculado o el chi cuadrado limite
        if ($chi_cuadrado_calculado > $chi_cuadrado_limite) {
            $resultado = false;
        } else {
            $resultado = true;
        }

        return view('testAleatoriedad.indexChi', compact('numerosIndividuales','fo','fe','chi_cuadrado_calculado','chi_cuadrado_limite','resultado','significancia','serie','iniciales','metodo','id_serie'));

    }

    public function poker(Request $request){

        $id_serie = $request->input('serie');
        if ($request->input('metodo') == 'fibonacci'){
            $valoresObtenidos = NumerosFibonacci::get()->where('user_id', auth()->user()->id)->where('id', $request->input('serie'))->first();
            $serie = json_decode($valoresObtenidos->valoresGeneradosF);
            $iniciales = json_decode($valoresObtenidos->valoresBaseF);
            $metodo = 'fibonacci';
        }else if ($request->input('metodo') == 'congruencias'){
            $valoresObtenidos = NumerosCongruencia::get()->where('user_id', auth()->user()->id)->where('id', $request->input('serie'))->first();
            $serie = json_decode($valoresObtenidos->valoresGeneradosC);
            $iniciales = json_decode($valoresObtenidos->valoresBaseC);
            $metodo = 'congruencias';
        }


        //contar la cantidad de numeros dentro de la serie que tenga 1 digito, 2 digitos, 3 digitos, 4 digitos y 5 digitos
        $contadores = array(0, 0, 0, 0, 0, 0); // Inicializa los contadores para cada longitud de número

        foreach ($serie as $numero) {
            $longitud = strlen((string) $numero); // Convierte el número a string y calcula su longitud
            if ($longitud <= 5) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
                $contadores[$longitud]++; // Incrementa el contador correspondiente
            }
        }

        $numeros_de_1_digito = array();
        $numeros_de_2_digitos = array();
        $numeros_de_3_digitos = array();
        $numeros_de_4_digitos = array();
        $numeros_de_5_digitos = array();

        foreach ($serie as $numero) {
            $longitud = strlen((string) $numero); // Convierte el número a string y calcula su longitud
            if ($longitud == 1) { // Solo contamos números con 1 dígito
                $numeros_de_1_digito[] = $numero;
            }else if ($longitud == 2) { // Solo contamos números con 2 dígitos
                $numeros_de_2_digitos[] = $numero;
            }else if ($longitud == 3) { // Solo contamos números con 3 dígitos
                $numeros_de_3_digitos[] = $numero;
            }else if ($longitud == 4) { // Solo contamos números con 4 dígitos
                $numeros_de_4_digitos[] = $numero;
            }else if ($longitud == 5) { // Solo contamos números con 5 dígitos
                $numeros_de_5_digitos[] = $numero;
            }
        }



        $clasificaciones1digito = array(
            'todos diferentes' => 0,
        );

        $clasificaciones2digitos = array(
            'todos diferentes' => 0,
            'par' => 0,
        );

        $clasificaciones3digitos = array(
            'todos diferentes' => 0,
            'par' => 0,
            'tercia' => 0,
        );

        $clasificaciones4digitos = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
        );

        $clasificaciones5digitos = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
            'full' => 0,
            'quintilla' => 0
        );

       foreach ($numeros_de_1_digito as $numero) {
            $this->clasificacionPoker($numero, $clasificaciones1digito);
        }

        foreach ($numeros_de_2_digitos as $numero) {
            $this->clasificacionPoker($numero, $clasificaciones2digitos);
        }

        foreach ($numeros_de_3_digitos as $numero) {
            $this->clasificacionPoker($numero, $clasificaciones3digitos);
        }

        foreach ($numeros_de_4_digitos as $numero) {
            $this->clasificacionPoker($numero, $clasificaciones4digitos);
        }

        foreach ($numeros_de_5_digitos as $numero) {
            $this->clasificacionPoker($numero, $clasificaciones5digitos);
        }

        //sumar los valores de los array con sus etiquetas correspondientes
        $clasificaciones = array(
            '1digito' => $clasificaciones1digito,
            '2digitos' => $clasificaciones2digitos,
            '3digitos' => $clasificaciones3digitos,
            '4digitos' => $clasificaciones4digitos,
            '5digitos' => $clasificaciones5digitos
        );

        $fo = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
            'full' => 0,
            'quintilla' => 0
        );

        foreach ($clasificaciones as $seccion => $clasificacion) {
            foreach ($clasificacion as $tipo => $cantidad) {
                $fo[$tipo] += $cantidad;
            }
        }

        //conseguimos la suma de la frecuencia observada general
        $sumatoria = array_sum($fo);

        //establecemos las probabilidades de ocurrencia de cada clasificacion
        $probabilidadesOcurrencia = array(
            'todos diferentes' => 0.3024,
            'par' => 0.504,
            'dos pares' => 0.108,
            'tercia' => 0.072,
            'full' => 0.009,
            'quintilla' => 0.0001
        );

        //calculamos la frecuencia esperada de cada clasificacion
        $fe = array();
        foreach ($probabilidadesOcurrencia as $clasificacion => $probabilidad) {
            $fe[$clasificacion] = $probabilidad * $sumatoria;
        }

        //calculamos el valor de chi cuadrada
        $chi_cuadrado_calculado = 0;

        foreach ($fe as $clasificacion => $frecuencia) {
            $chi_cuadrado_calculado += pow($fo[$clasificacion] - $frecuencia, 2) / $frecuencia;
        }

        //calculamos el valor de chi cuadrada tabulado
        /*
         Numeros de tabla de chi
            1 - 3,8415
            2 - 5,9915
            3 - 7,8147
            4 - 9,4877
            5 - 11,0705
            6 - 12,5916
        */

        $significancia = 0.05;
        $grados_de_libertad = count($fe) - 1;

        switch ($grados_de_libertad) {
            case 1:
                $chi_cuadrado_limite = 3.8415;
                break;
            case 2:
                $chi_cuadrado_limite = 5.9915;
                break;
            case 3:
                $chi_cuadrado_limite = 7.8147;
                break;
            case 4:
                $chi_cuadrado_limite = 9.4877;
                break;
            case 5:
                $chi_cuadrado_limite = 11.0705;
                break;
            case 6:
                $chi_cuadrado_limite = 12.5916;
                break;
            default:
                $chi_cuadrado_limite = 0;
                break;
        }

        //verificar cual es mayor si el chi cuadrado calculado o el chi cuadrado limite
        if ($chi_cuadrado_calculado > $chi_cuadrado_limite) {
            $resultado = false;
        } else {
            $resultado = true;
        }

        return view('testAleatoriedad.indexPoker', compact('fo','fe','chi_cuadrado_calculado','chi_cuadrado_limite','resultado','significancia','serie','iniciales','metodo','id_serie','grados_de_libertad'));
    }

    public function clasificacionPoker($numero, &$clasificacionActual) {

        //idea general
        /*Ir cambiando las cantidades de cifras e ir buscando las clasificaciones por cifra
        asi vas poniendo especificamente en cada seccion el condicional necesario
        DEBERIA FUNCIONAR

        EJEMPLO

        $numero = 1134;

        entonces miras con el dd que sale en $valores y count($valores)

        y fijas donde caerian ocurrencias de ese tipo y vas a comprobar en los ifs entonces
        cuando encuentres si no hay establces condicional hasta cubrir todos los casos

        AYUDA ME MUERO SON LAS 1:51 A MIMIR XD

        */

/*        $numero = 11121;*/

        $digitos = str_split($numero);
        $conteo = array_count_values($digitos);
        $valores = array_values($conteo);
        rsort($valores);

/*        dd($digitos,$conteo,$valores,count($valores));*/

        if (count($valores) == 5) {
            if ($valores[0] == 1 && $valores[1] == 1 && $valores[2] == 1 && $valores[3] == 1 && $valores[4] == 1){
                $clasificacionActual['todos diferentes']++;
            }
        } elseif (count($valores) == 4) {
            if ($valores[0] == 1 && $valores[1] == 1 && $valores[2] == 1 && $valores[3] == 1) {
                $clasificacionActual['todos diferentes']++;
            }elseif ($valores[0] == 2 && $valores[1] == 1 && $valores[2] == 1 && $valores[3] == 1) {
                $clasificacionActual['par']++;
            }
        } elseif (count($valores) == 3) {
            if($valores[0] == 1 && $valores[1] == 1 && $valores[2] == 1){
                $clasificacionActual['todos diferentes']++;
            }elseif ($valores[0] == 2 && $valores[1] == 1 && $valores[2] == 1){
                $clasificacionActual['par']++;
            }elseif ($valores[0] == 2 && $valores[1] == 2 && $valores[2] == 1){
                $clasificacionActual['dos pares']++;
            }elseif ($valores[0] == 3 && $valores[1] == 1 && $valores[2] == 1){
                $clasificacionActual['tercia']++;
            }
        } elseif (count($valores) == 2) {
            if($valores[0] == 1 && $valores[1] == 1){
                $clasificacionActual['todos diferentes']++;
            }elseif ($valores[0] == 2 && $valores[1] == 2) {
                $clasificacionActual['dos pares']++;
            }elseif($valores[0] == 2 && $valores[1] == 1){
                $clasificacionActual['par']++;
            }elseif($valores[0] == 3 && $valores[1] == 1){
                $clasificacionActual['tercia']++;
            }elseif($valores[0] == 3 && $valores[1] == 2){
                $clasificacionActual['full']++;
            }
        } elseif(count($valores) == 1) {
            if ($valores[0] == 2) {
                $clasificacionActual['par']++;
            }elseif($valores[0] == 3){
                $clasificacionActual['tercia']++;
            }elseif($valores[0] == 5){
                $clasificacionActual['quintilla']++;
            }else {
                $clasificacionActual['todos diferentes']++;
            }
        }
    }

    public function storeChi(Request $request)
    {

        /*        dd($request->all());*/

        $chi = new Resultadochi();
        $chi->metodo = $request->input('metodo');
        $chi->fo = $request->input('fo');
        $chi->fe = $request->input('fe');
        $chi->chi_cuadrado_calculado = $request->input('chi_cuadrado_calculado');
        if ($request->input('resultado') !== null) {
            $chi->resultado = true;
        } else {
            $chi->resultado = false;
        }
        if ($request->input('metodo') == 'fibonacci') {
            $chi->id_f = $request->input('id_serie');
            $chi->id_c = null;
        } else if ($request->input('metodo') == 'congruencias'){
            $chi->id_c = $request->input('id_serie');
            $chi->id_f = null;
        }

        $chi->save();

        return redirect()->route('TA.indexChi')->with('chi','ok');
    }
    public function storePoker(Request $request)
    {

/*        dd($request->all());*/

        $poker = new Resultadopoker();
        $poker->metodo = $request->input('metodo');
        $poker->fo = $request->input('fo');
        $poker->fe = $request->input('fe');
        $poker->chi_cuadrado_calculado = $request->input('chi_cuadrado_calculado');
        $poker->chi_cuadrado_limite = $request->input('chi_cuadrado_limite');
        $poker->grados_de_libertad = $request->input('grados_libertad');
        if ($request->input('resultado') !== null) {
            $poker->resultado = true;
        } else {
            $poker->resultado = false;
        }
        if ($request->input('metodo') == 'fibonacci') {
            $poker->id_f = $request->input('id_serie');
            $poker->id_c = null;
        } else if ($request->input('metodo') == 'congruencias'){
            $poker->id_c = $request->input('id_serie');
            $poker->id_f = null;
        }

        $poker->save();

        return redirect()->route('TA.indexPoker')->with('poker','ok');
    }
}
