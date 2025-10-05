<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function calcularSemana($data_inicial, $data_final)
    {
        $data_inicial = Carbon::parse($data_inicial);
        $data_final = Carbon::parse($data_final);
        $quantidadeSemanas = (int) $data_inicial->diffInWeeks($data_final);
        return $quantidadeSemanas;
    }
}
