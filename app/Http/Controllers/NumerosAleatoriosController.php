<?php

namespace App\Http\Controllers;

use App\Models\NumerosCongruencia;
use App\Models\NumerosFibonacci;
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

        $this->fibonacciFormula($v1, $v2, $cantidad, $a, $numeros);

        return view('numerosAleatorios.indexF', compact('numeros','a','cantidad'));
    }

    public function fibonacciFormula($v1, $v2, $cantidad, $a, &$numeros){

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

        for ($i=1; $i < $v2; $i++) {
            $numeros[] = rand(1000,9999);
        }

        /*V i+1 =(a V i + c V i-k) mod m*/

        for ($i=$v2; $i < $cantidad; $i++) {
            $v3 = (($a*$numeros[$i-$v2])+($c*$numeros[$i-1])) % $m;
            $numeros[] = $v3;
        }

        return view('numerosAleatorios.indexC', compact('numeros','a','c','m','v1','v2','cantidad'));
    }

    public function storeF(Request $request){

/*        dd($request->all());*/

        //obtenemos los datos
        $numerosA = json_decode($request->input('numerosGenerados'),true);
        $a = $request->input('control');
        $cantidad = $request->input('cantidad');
        $v1 = $numerosA[0];
        $v2 = $numerosA[1];

        //guardamos los datos
        $datos = new NumerosFibonacci();
        $datos->valoresBaseF = "C=".$cantidad." A=".$a." V1=".$v1." V2=".$v2;
        $datos->valoresGeneradosF = json_encode($numerosA);//guardamos los numeros generados en formato json para poder recuperarlos
        $datos->user_id = auth()->user()->id;

/*        dd($datos);*/

        $datos->save();

        return redirect()->route('NA.indexF')->with('aleatorio','ok');
    }

    public function storeC(Request $request){

/*        dd($request->all());*/
        //obtenemos los datos
        $numerosA = json_decode($request->input('numerosGenerados'),true);
        $a = $request->input('a');
        $c = $request->input('c');
        $m = $request->input('m');
        $cantidad = $request->input('cantidad');
        $v1 = $numerosA[0];
        $v2 = $request->input('v2');

        //guardamos los datos
        $datos = new NumerosCongruencia();
        $datos->valoresBaseC = "C=".$cantidad." A=".$a." C=".$c." M=".$m." V1=".$v1." V2=".$v2;
        $datos->valoresGeneradosC = json_encode($numerosA);//guardamos los numeros generados en formato json para poder recuperarlos
        $datos->user_id = auth()->user()->id;
/*        dd($datos);*/
        $datos->save();

        return redirect()->route('NA.indexC')->with('aleatorio','ok');
    }
}
