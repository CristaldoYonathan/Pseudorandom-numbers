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

      /*  $fo_general = array(
            'todos diferentes' => 5,
            'par' => 1,
            'dos pares' => 3,
            'tercia' => 3,
            'full' => 4,
            'quintilla' => 1
        );*/

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


        //separamos las fe con valor menor a 5 y valor mayor a 5 en 2 array diferentes
        $fe_menor_5 = array();
        $fe_mayor_5 = array();
        $sumas_mayor_5 = array();
        foreach ($fe as $clasificacion => $frecuencia) {
            if ($frecuencia < 5) {
                $fe_menor_5[$clasificacion] = $frecuencia;
            } else {
                $fe_mayor_5[$clasificacion] = $frecuencia;
            }
        }

    /*    $fe_menor_5 = array(
            'todos diferentes' => 2.4192,
            'par' => 4.032,
            'dos pares' => 0.864,
            'tercia' => 0.576,
            'full' => 0.072,
            'quintilla' => 0.0008
        );*/

        //recorremos el array de fe menores a 5 y sumamos los valores de las probabilidades hasta dar 5,
        // si llega a 5 deternse y no seguir sumando. Sin usar funciones externas
        $fe_menor_5 = array_reverse($fe_menor_5);
/*        $fo_general = array_reverse($fo_general);*/
        $suma = 0;
        $suma_fo = 0;
        $posicion = 0;
        $ultima_clasificacion = array();
        $fo_final = array();
        foreach ($fe_menor_5 as $clasificacion => $frecuencia) {
            $suma_temporal = $suma + $frecuencia;
            $suma = $suma_temporal;
            $suma_fo += $fo_general[$clasificacion];
            if ($suma >= 5) {
                $sumas_mayor_5[$posicion] = $suma; // Agregar la suma mayor a 5 al array
                $ultima_clasificacion[] = $clasificacion; // Guardar la última clasificación que llegó a una suma mayor o igual a 5
/*                $fo_final[$posicion] = $suma_fo; // Guardar la suma de las frecuencias observadas de la última clasificación*/
            }elseif ($suma_temporal < 5) {
                $sumas_mayor_5[$posicion] = $suma; // Agregar la suma menor a 5 al array
                /*$fo_final[$posicion] = $suma_fo;*/ // Guardar la suma de las frecuencias observadas de la última clasificación
            }
            if ($suma_temporal >= 5) {
                $suma = 0;
                $posicion++;
            }

        }
        if(min($sumas_mayor_5) < 5){
            $ultima_clasificacion[] = 'todos diferentes';
        }

        $sumas_mayor_5 = array_reverse($sumas_mayor_5);
        $ultima_clasificacion = array_reverse($ultima_clasificacion);
/*        $fo_final = array_reverse($fo_final);*/

        $fe_final = array_combine($ultima_clasificacion,$sumas_mayor_5);
/*        $fo_final = array_combine($ultima_clasificacion,$fo_final);*/

        //calculamos el valor de chi cuadrada
        $chi_cuadrada = 0;

        /*foreach ($fe_final as $clasificacion => $frecuencia) {
            $chi_cuadrada += pow($fo_final[$clasificacion] - $frecuencia, 2) / $frecuencia;
        }*/


        dd($fe_menor_5,$fe_mayor_5,$fe,$suma,$sumas_mayor_5,$ultima_clasificacion,$fe_final,$fo_general,$fo_final);


        //calculamos el valor de chi cuadrada tabulado

        dd($serie,$numeros_de_1_digito,$clasificaciones1digito, $numeros_de_2_digitos,$clasificaciones2digitos,
            $numeros_de_3_digitos,$clasificaciones3digitos, $numeros_de_4_digitos,$clasificaciones4digitos, $numeros_de_5_digitos,$clasificaciones5digitos,$fo_general,$sumatoria,$fe,$chi_cuadrada);
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
