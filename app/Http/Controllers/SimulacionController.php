<?php

namespace App\Http\Controllers;

use App\Models\NumerosCongruencia;
use App\Models\Numerosfibonacci;
use Illuminate\Http\Request;

class SimulacionController extends Controller{


    //crear la funcion index
    public function indexSimulacion(){

        $seriesF = numerosfibonacci::all();
        $seriesC = numeroscongruencia::all();

        return view('simulacion.simulacionRepresa', compact('seriesF','seriesC'));
    }

    public function simulacion(Request $request){

        if ($request->input('metodo') == 'congruencias') {
            $series = json_decode(NumerosCongruencia::get()->where('id', $request->input('serie'))->first()->valoresGeneradosC);
        } elseif ($request->input('metodo') == 'fibonacci') {
            $series = json_decode(Numerosfibonacci::get()->where('id', $request->input('serie'))->first()->valoresGeneradosF);
        }



        if (isset($series)){
            $maxValue = max($series);

            $cantidadDigitos = strlen((string)$maxValue);

            //agregar ceros a la derecha del 1
            $divisor = (pow(10, +$cantidadDigitos));

            $normalizedArray = array_map(function($value) use ($divisor) { //Arreglar error
                return $value / $divisor;
            }, $series);
        }

        /*dd($series, $normalizedArray, $maxValue);*/

/*        dd($normalizedArray);*/

        $marcas_clases = [-1500, -500, 500, 1000, 1500, 2000];
        $Fx = [0.078,0.174,0.248,0.248,0.174,0.078];
        //obtener frecuencia acumulada de Fx
        $Px = [];
        $acumulada = 0;
        foreach ($Fx as $f) {
            $acumulada += $f;
            $Px[] = $acumulada;
        }

        //obtener limite inferior y superior de cada clase
        $limite_inferior = [];
        $limite_superior = [];

        $limite_inferior[0] = 0;
        for ($i = 1; $i <= 5; $i++) {
            $limite_inferior[$i] = $Px[$i-1] + 0.001;
            $limite_superior[$i-1] = $Px[$i-1];
        }

        $limite_superior[5] = $Px[5];

        //Valor inicial del caudal de agua
        $caudal = 20000;
        $caudalInicial = $caudal;
        $caudales = [];
        $caudalesSinDesborde = [];
        $energiaGenerada = [];
        $energiaPerdida = [];

        foreach ($normalizedArray as $value) {
            if ($value >= $limite_inferior[0] && $value <= $limite_superior[0]) {
                $caudal += $marcas_clases[0];
            }elseif ($value >= $limite_inferior[1] && $value <= $limite_superior[1]) {
                $caudal += $marcas_clases[1];
            }elseif ($value >= $limite_inferior[2] && $value <= $limite_superior[2]) {
                $caudal += $marcas_clases[2];
            }elseif ($value >= $limite_inferior[3] && $value <= $limite_superior[3]) {
                $caudal += $marcas_clases[3];
            }elseif ($value >= $limite_inferior[4] && $value <= $limite_superior[4]) {
                $caudal += $marcas_clases[4];
            }elseif ($value >= $limite_inferior[5] && $value <= $limite_superior[5]) {
                $caudal += $marcas_clases[5];
            }

            $caudalesSinDesborde[] = $caudal;
            if ($caudal >= 15000 && $caudal < 25000) {
                $caudal -= 3000;
                $energiaGenerada[] = 3200 ;
                $energiaPerdida[] = 9600;
            } elseif ($caudal >= 25000 && $caudal < 32000) {
                $caudal -= 6000;
                $energiaGenerada[] = 6400;
                $energiaPerdida[] = 6400;
            } elseif ($caudal >= 32000 && $caudal < 40000) {
                $caudal -= 9000;
                $energiaGenerada[] = 9600;
                $energiaPerdida[] = 3200;
            } elseif ($caudal >= 40000) {
                $caudal -= 12000;
                $energiaGenerada[] = 12800;
                $energiaPerdida[] = 0;
            }else{
                $energiaGenerada[] = 0;
                $energiaPerdida[] = 12800;
            }
            $caudales[] = $caudal;
        }

        // Variables para almacenar la información
        $infocaudal = [
            'promedio' => 0,
            'maximo' => 0,
            'minimo' => 0
        ];
        $infocompuertas = [
            'primera' => 0,
            'segunda' => 0,
            'tercera' => 0,
            'cuarta' => 0
        ];
        $infoalerta = [
            'activacion' => 0
        ];

        // Cálculo del caudal promedio, máximo y mínimo
        $caudal_total = 0;
        $caudal_maximo = 0;
        $caudal_minimo = PHP_INT_MAX;
        $num_caudales = count($caudales);

        foreach ($caudales as $caudal) {
            $caudal_total += $caudal;
            $caudal_maximo = max($caudal_maximo, $caudal);
            $caudal_minimo = min($caudal_minimo, $caudal);
        }

        $infocaudal['promedio'] = round($caudal_total / $num_caudales,3);
        $infocaudal['maximo'] = $caudal_maximo;
        $infocaudal['minimo'] = $caudal_minimo;

        // Conteo de aperturas de las compuertas y activaciones de alerta roja
        foreach ($caudalesSinDesborde as $caudal) {
            if ($caudal >= 15000 && $caudal < 25000) {
                $infocompuertas['primera']++;
            } elseif ($caudal >= 25000 && $caudal < 32000) {
                $infocompuertas['primera']++;
                $infocompuertas['segunda']++;
            } elseif ($caudal >= 32000 && $caudal < 40000) {
                $infocompuertas['primera']++;
                $infocompuertas['segunda']++;
                $infocompuertas['tercera']++;
            } elseif ($caudal >= 40000) {
                $infocompuertas['primera']++;
                $infocompuertas['segunda']++;
                $infocompuertas['tercera']++;
                $infocompuertas['cuarta']++;
            }
            if ($caudal > 45000) {
                $infoalerta['activacion']++;
            }
        }


        return view('simulacion.simulacionRepresa', compact('normalizedArray','marcas_clases','limite_inferior','limite_superior','caudal','caudales','infocaudal','infocompuertas','infoalerta','caudalesSinDesborde','energiaGenerada','energiaPerdida','caudalInicial'));

    }

}
