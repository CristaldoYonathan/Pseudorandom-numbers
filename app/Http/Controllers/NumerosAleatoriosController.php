<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumerosAleatoriosController extends Controller
{

    public function indexF()
    {
        return view('numerosAleatorios.indexF');
    }
    public function indexC()
    {
        return view('numerosAleatorios.indexC');
    }

    public function fibonacci(Request $request){

//        dd($request->all());

        $numeros = [];
        $v1 = $request->input('semillav1');
        $v2 = $request->input('semillav2');
        $cantidad = $request->input('cantidad');
        $a = $request->input('control');
        $numeros[0] = intval($v1);
        $numeros[1] = intval($v2);

        for ($i=2; $i < $cantidad; $i++) {
            if ($v2+$v1 <= $a){
                $k = 0;
                $v3 = $v1+$v2+$k*$a;
                $v1 = $v2;
                $v2 = $v3;
                $numeros[] = $v3;
            }else{
                $k = -1;
                $v3 = $v1+$v2+$k*$a;
                $v1 = $v2;
                $v2 = $v3;
                $numeros[] = $v3;
            }
        }

/*        $this->chiCuadradoUnformidad($numeros);*/

        return view('numerosAleatorios.indexF', compact('numeros','a'));
    }

    public function congruencias(Request $request){
        $numeros = [];
        $v1 = $request->input('semillavi');
        $v2 = $request->input('semiillavik');
        $cantidad = $request->input('cantidadCong');
        $a = $request->input('constanteA');
        $c = $request->input('constanteC');
        $m = $request->input('constanteM');
        $numeros[0] = intval($v1);
        $numeros[1] = intval($v2);

        for ($i=2; $i < $cantidad; $i++) {
            $v3 = (($a*$v1)+($c*$v2)) % $m;
            $numeros[] = $v3;
            $v1 = $v2;
            $v2 = $v3;

        }

        return view('numerosAleatorios.indexC', compact('numeros','a','c','m','v1','v2'));
    }

    public function chiCuadradoUnformidad($numeros){


        $significancia = 0.05;//Nivel de signficacia es decir alfa
        $n = count($numeros);//Cantidad de numeros generados(tamaño de la muestra)
        $k = sqrt($n);//Cantidad de intervalos
        $rango_intervalo = max($numeros)/$k;//Amplitud de los intervalos
        $intervalos = array(array(1,2));//Matriz de intervalos(limites inferiores y superiores)
        $fo = array($k);//Frecuencia observada
        $fe = $n/$k;//Frecuencia esperada
        $chiCalculado = 0;//Chi cuadrado calculado
        $chiLimite = 0;//Chi cuadrado limite
        $gradosLibertad = $k-1;//Grados de libertad

        //Se llena la matriz de intervalos
        for($i = 0; $i < $k; $i++){
            $intervalos[$i][0] = $i*$rango_intervalo;
            $intervalos[$i][1] = ($i+1)*$rango_intervalo;
        }

        //Se llena el arreglo de frecuencia observada de cada intervalo
        for($i = 0; $i < $k; $i++){
            $fo[$i] = 0;
            for($j = 0; $j < $n; $j++){
                if($numeros[$j] >= $intervalos[$i][0] && $numeros[$j] < $intervalos[$i][1]){
                    $fo[$i]++;
                }
            }
        }

        //Se calcula el chi cuadrado
        for($i = 0; $i < $k; $i++){
            $chiCalculado += pow(($fo[$i]-$fe),2)/$fe;
        }

        //Se calcula el chi cuadrado limite tomando los grados de libertad y el nivel de significancia
        $chiLimite = stats_dens_chisquare_inv($significancia, $gradosLibertad);


////        verificar hipotesis
//        if($chiCalculado < $chiLimite){
//            echo "Se acepta la hipotesis de uniformidad";
//        }else{
//            echo "Se rechaza la hipotesis de uniformidad";
//        }
//        sort($numeros);

        dd($numeros,$n,$k,$rango_intervalo,$intervalos, $fo, $fe, $chiCalculado,$chiLimite);


    }
}
