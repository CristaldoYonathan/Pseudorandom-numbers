<?php

namespace App\Http\Controllers;

use App\Models\NumerosCongruencia;
use App\Models\NumerosFibonacci;
use App\Models\Resultadochi;
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
        $significancia = 0.05;
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
            if ($longitud == 1) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
                $numeros_de_1_digito[] = $numero;
            }else if ($longitud == 2) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
                $numeros_de_2_digitos[] = $numero;
            }else if ($longitud == 3) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
                $numeros_de_3_digitos[] = $numero;
            }else if ($longitud == 4) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
                $numeros_de_4_digitos[] = $numero;
            }else if ($longitud == 5) { // Solo contamos números con 1, 2, 3, 4 o 5 dígitos
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
            'full' => 0,
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
            $clasificaciones1digito = $this->clasificacionPoker($numero, $clasificaciones1digito);
        }

        foreach ($numeros_de_2_digitos as $numero) {
            $clasificaciones2digitos = $this->clasificacionPoker($numero, $clasificaciones2digitos);
        }

        foreach ($numeros_de_3_digitos as $numero) {
            $clasificaciones3digitos = $this->clasificacionPoker($numero, $clasificaciones3digitos);
        }

        foreach ($numeros_de_4_digitos as $numero) {
            $clasificaciones4digitos = $this->clasificacionPoker($numero, $clasificaciones4digitos);
        }

        foreach ($numeros_de_5_digitos as $numero) {
            $clasificaciones5digitos = $this->clasificacionPoker($numero, $clasificaciones5digitos);
        }

        //sumar los valores de los array con sus etiquetas correspondientes
        $clasificaciones = array(
            '1digito' => $clasificaciones1digito,
            '2digitos' => $clasificaciones2digitos,
            '3digitos' => $clasificaciones3digitos,
            '4digitos' => $clasificaciones4digitos,
            '5digitos' => $clasificaciones5digitos
        );

        $fo_general = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
            'full' => 0,
            'quintilla' => 0
        );

        foreach ($clasificaciones as $seccion => $clasificacion) {
            foreach ($clasificacion as $tipo => $cantidad) {
                $fo_general[$tipo] += $cantidad;
            }
        }

        //conseguimos la suma de la frecuencia observada general
        $sumatoria = array_sum($fo_general);

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

        //agrupamos las frecuencias esperadas que sean menores a 5 en una sola clasificacion
        $fe_agrupadas = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
            'full' => 0,
            'quintilla' => 0
        );

        $fo_agrupadas = array(
            'todos diferentes' => 0,
            'par' => 0,
            'dos pares' => 0,
            'tercia' => 0,
            'full' => 0,
            'quintilla' => 0
        );

        $sum_fe_agrupadas = 0;
        $continuar = false;

        foreach ($fe as $clasificacion => $frecuencia) {
            if ($frecuencia < 5) {
                $fe_agrupadas['todos diferentes'] += $frecuencia;
                $fo_agrupadas['todos diferentes'] += $fo_general[$clasificacion];
                $sum_fe_agrupadas += $frecuencia;
                if ($sum_fe_agrupadas >= 5){
                    $continuar = true;
                }
            }else{
                $fe_agrupadas[$clasificacion] = $frecuencia;
                $fo_agrupadas[$clasificacion] = $fo_general[$clasificacion];
            }

            if ($continuar) {
                break;
            }
        }

        dd($fe_agrupadas, $fo_agrupadas);

        //despejamos los valores de frecuencia esperada que sean distintos de 0
        $fe_final = array();

        foreach ($fe_agrupadas as $clasificacion => $frecuencia) {
            if ($frecuencia > 0) {
                $fe_final[$clasificacion] = $frecuencia;
            }
        }

        //despejamos los valores de frecuencia observada que sean distintos de 0
        $fo_final = array();

        foreach ($fo_agrupadas as $clasificacion => $frecuencia) {
            if ($frecuencia > 0) {
                $fo_final[$clasificacion] = $frecuencia;
            }
        }

        //calculamos el valor de chi cuadrada
        $chi_cuadrada = 0;

        foreach ($fe_final as $clasificacion => $frecuencia) {
            $chi_cuadrada += pow($fo_final[$clasificacion] - $frecuencia, 2) / $frecuencia;
        }

        //calculamos el valor de chi cuadrada tabulado



        /*$resultado = $this->clasificarPoker($serie);*/

        dd($serie,$numeros_de_1_digito,$clasificaciones1digito, $numeros_de_2_digitos,$clasificaciones2digitos,
            $numeros_de_3_digitos,$clasificaciones3digitos, $numeros_de_4_digitos,$clasificaciones4digitos, $numeros_de_5_digitos,$clasificaciones5digitos,$fo_general,$sumatoria,$fe,$fe_agrupadas,$fe_final,$fo_agrupadas,$fo_final,$chi_cuadrada);
    }

    public function clasificacionPoker($numero, $clasificacion){

        $numerosIndividuales = [];
        $ntd = [];
        $npar = [];
        $nter = [];
        $ndospar = [];
        $nfull = [];
        $nquintilla = [];

        $fo = array_fill(0, 10, 0); // Inicializar con 0 en todas las claves del 0 al 9

        $cadena = strval($numero);
        foreach (str_split($cadena) as $digito) {
            $valor = intval($digito);
            $numerosIndividuales[] = $valor;
            $fo[$valor]++;
        }

        //clasificar la cantidad que aparece en fo con los valores del array $clasificaciones
        foreach ($fo as $frecuencia) {
            if ($frecuencia == 5) {
                $clasificacion['quintilla']++;
                $nquintilla[] = $numero;
            } elseif ($frecuencia == 3) {
                $clasificacion['tercia']++;
                $nter[] = $numero;
            }
            /*elseif ($frecuencia == 1 && count(array_unique($numerosIndividuales)) == 4) {
                $clasificacion['todos diferentes']++;
                $ntd[] = $numero;
            }*/
        }

        if (in_array(4, $fo)) {
            $clasificacion['full']++;
            $nfull[] = $numero;
        } elseif (in_array(2, $fo) && count(array_keys($fo, 2)) == 2) {
            $clasificacion['dos pares']++;
            $ndospar[] = $numero;
        }elseif (in_array(2, $fo) && count(array_keys($fo, 2)) == 1) {
            $clasificacion['par']++;
            $npar[] = $numero;
        }

        foreach ($ntd as $numero) {
           echo $numero." todos diferentes <br>";
        }
        foreach ($npar as $numero) {
           echo $numero." par <br>";
        }
        foreach ($ndospar as $numero) {
           echo $numero." dos pares <br>";
        }
        foreach ($nter as $numero) {
           echo $numero." tercia <br>";
        }
        foreach ($nfull as $numero) {
           echo $numero." full <br>";
        }
        foreach ($nquintilla as $numero) {
           echo $numero." quintilla <br>";
        }

        return $clasificacion;

    }

    /*function clasificarPoker($numeros) {

        $clasificaciones = array(
            "TD" => array(),
            "1P" => array(),
            "2P" => array(),
            "T" => array(),
            "F" => array(),
            "Q" => array()
        );

        foreach ($numeros as $numero) {
            // Eliminar los espacios en blanco y cualquier otro carácter no numérico
            $numero = preg_replace("/[^0-9.]/", "", $numero);

            // Extraer los dígitos individuales del número y contarlos
            $digits = str_split($numero);
            $count = array_count_values($digits);

            // Clasificar la mano de poker
            if (count($count) == count($digits)) {
                array_push($clasificaciones["TD"], $numero);
            } elseif (count($count) == 4) {
                array_push($clasificaciones["1P"], $numero);
            } elseif (count($count) == 3) {
                if (in_array(3, $count)) {
                    array_push($clasificaciones["T"], $numero);
                } else {
                    array_push($clasificaciones["2P"], $numero);
                }
            } elseif (count($count) == 2) {
                if (in_array(4, $count)) {
                    array_push($clasificaciones["F"], $numero);
                } else {
                    array_push($clasificaciones["1P"], $numero);
                    array_push($clasificaciones["T"], $numero);
                }
            } elseif (count($count) == 1) {
                array_push($clasificaciones["Q"], $numero);
            }
        }

        return $clasificaciones;
    }*/


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
}
