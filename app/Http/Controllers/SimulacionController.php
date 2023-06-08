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

        if ($request->input('metodo') == 'congruencia') {
            $series = json_decode(NumerosCongruencia::get()->where('id', $request->input('serie'))->first()->valoresGeneradosC);
        } elseif ($request->input('metodo') == 'fibonacci') {
            $series = json_decode(Numerosfibonacci::get()->where('id', $request->input('serie'))->first()->valoresGeneradosF);
        }

        if (isset($series)){
            $maxValue = max($series);

            $normalizedArray = array_map(function($value) use ($maxValue) {
                return $value / $maxValue;
            }, $series);
        }

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
        $caudales = [];

        //obtener las frecuencias observadas de cada clase que esté en el limite inferior y superior tneiendo en cuenta el array $normalizedArray
        $frecuencia_observada = [];
        //realizamos la suma y resta al caudal inicial teniedo en cuenta el array $normalizedArray y las marcas de clase
        for ($i = 0; $i < 6; $i++) {
            $frecuencia_observada[$i] = 0;
            foreach ($normalizedArray as $value) {
                if ($value >= $limite_inferior[$i] && $value <= $limite_superior[$i]) {
                    $frecuencia_observada[$i]++;
                    $caudal += $marcas_clases[$i];
                    $caudales[] = $caudal;
                }
            }
        }
        if (isset($normalizedArray)){
            $frecuencia_observada[5]++;
            $caudal += $marcas_clases[5];
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
        foreach ($caudales as $caudal) {
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


        return view('simulacion.simulacionRepresa', compact('normalizedArray','marcas_clases','limite_inferior','limite_superior','frecuencia_observada','caudal','caudales','infocaudal','infocompuertas','infoalerta'));

    }

}
