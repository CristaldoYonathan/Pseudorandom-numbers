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
//        $numeros = [];
        $v1 = $request->input('semillav1');
//        $cantidad = $request->input('cantidad');
//        $a = $request->input('control');
//        $c = $request->input('control2');
//        $m = $request->input('control3');
        $numeros[0] = intval($v1);
//
//        for ($i=1; $i < $cantidad; $i++) {
//            $v2 = ($a*$v1+$c)%$m;
//            $numeros[] = $v2;
//            $v1 = $v2;
//        }

        return view('numerosAleatorios.congruencia', compact('numeros'));
    }
}
