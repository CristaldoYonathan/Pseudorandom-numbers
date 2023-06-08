<?php

namespace App\Http\Controllers;

use App\Models\NumerosCongruencia;
use App\Models\Numerosfibonacci;
use App\Models\Resultadochi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcaClaseController extends Controller
{
    public function indexClases()
    {

        //recuperar las series con resultado 1 de la tabla resultadochi con eloquent
        $series = Resultadochi::get();

        foreach ($series as $serie) { //Arreglar error
            $seriesF = Numerosfibonacci::all();
            $seriesC = NumerosCongruencia::all();
        }

        if (isset($seriesF) && isset($seriesC)) {
            return view('marcaClases.indexMarcaClase', compact('seriesF', 'seriesC'));
        } else {
            return view('marcaClases.indexMarcaClase')->with('clases', 'no');
        }
    }

    public function marcaclases(Request $request)
    {
        if ($request->input('metodo') == 'congruencia') {
            $series = json_decode(NumerosCongruencia::get()->where('id', $request->input('serie'))->first()->valoresGeneradosC);
        } elseif ($request->input('metodo') == 'fibonacci') {
            $series = json_decode(Numerosfibonacci::get()->where('id', $request->input('serie'))->first()->valoresGeneradosF);
        }

        /*$series = [
            234, 587, 409, 674, 152, 876, 345, 982, 743, 521,
            306, 458, 763, 213, 678, 492, 831, 574, 326, 759,
            418, 923, 627, 394, 582, 749, 210, 865, 537, 416,
            683, 297, 871, 534, 428, 607, 918, 685, 312, 786,
            531, 679, 452, 824, 579, 368, 541, 837, 496, 725,
            359, 632, 404, 916, 753, 291, 617, 482, 835, 593,
            405, 723, 867, 520, 348, 698, 243, 670, 875, 312,
            503, 787, 436, 942, 579, 328, 867, 417, 652, 368,
            815, 574, 394, 731, 529, 446, 705, 912, 687, 345,
            781, 479, 362, 654, 221, 745, 597, 419, 864, 512
        ];*/

        if (isset($series)){
            $maxValue = max($series);

            $normalizedArray = array_map(function($value) use ($maxValue) {
                return $value / $maxValue;
            }, $series);
        }

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

        //obtener las frecuencias observadas de cada clase que esté en el limite inferior y superior tneiendo en cuenta el array $normalizedArray
        $frecuencia_observada = [];
        //ordenar $normalizedArray
        sort($normalizedArray);
        for ($i = 0; $i < 6; $i++) {
            $frecuencia_observada[$i] = 0;
            foreach ($normalizedArray as $value) {
                if ($value >= $limite_inferior[$i] && $value <= $limite_superior[$i]) {
                    $frecuencia_observada[$i]++;
/*                    echo ' valor='. $value .'='. $limite_inferior[$i] .' valor='. $value .'='. $limite_superior[$i] . '<br>';*/
                }
            }
        }
        if (isset($normalizedArray)){
            $frecuencia_observada[5]++;
        }

        //realizar suma frecuencia observada
        $sumatoria = array_sum($frecuencia_observada);

        //calcular la probabilidad de ocurrencia de cada clase
        $probabilidad_ocurrencia = [];
        for ($i = 0; $i < 6; $i++) {
            $probabilidad_ocurrencia[$i] = $frecuencia_observada[$i] / $sumatoria;
        }

        //graficar $Fx y $probabilidad_ocurrencia

        return view('marcaClases.indexMarcaClase', compact('series', 'marcas_clases', 'Fx', 'limite_inferior', 'limite_superior', 'frecuencia_observada', 'probabilidad_ocurrencia'));
        dd($request->all(),$series,$maxValue,$normalizedArray, $marcas_clases, $Fx, $Px, $limite_inferior, $limite_superior, $frecuencia_observada, $sumatoria, $probabilidad_ocurrencia);
    }
}
