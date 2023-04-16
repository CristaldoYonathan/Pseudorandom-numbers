<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumerosAleatoriosController extends Controller
{
    public function index()
    {
        return view('numerosAleatorios.index');
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

        return view('numerosAleatorios.show', compact('numeros'));
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

        for ($i=1; $i < $cantidad; $i++) {
            $v3 = (($a*$v1)+($c*$v2)) % $m;
            $numeros[] = $v3;
            $v1 = $v2;
            $v2 = $v3;

        }

//        function congruenciaFundamental($v1, $v2, $a, $k, $m, $cantidad){
//            $v = array($v1, $v2);
//            for($i = 2; $i < $cantidad; $i++){
//                $v[$i] = (($a * $v[$i-1]) + ($k * $v[$i-2])) % $m;
//            }
//            return $v;
//        }
        dd($numeros);

        return view('numerosAleatorios.congruencia', compact('numeros'));
    }
}
