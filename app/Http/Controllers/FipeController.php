<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FipeController extends Controller
{
    private string $base = 'https://parallelum.com.br/fipe/api/v1/carros';

    public function marcas()
    {
        return response()->json(Http::get("{$this->base}/marcas")->json());
    }

    public function modelos(int $marca)
    {
        $res = Http::get("{$this->base}/marcas/{$marca}/modelos");
        return response()->json($res->json()['modelos'] ?? []);
    }

    public function anos(int $marca, int $modelo)
    {
        return response()->json(Http::get("{$this->base}/marcas/{$marca}/modelos/{$modelo}/anos")->json());
    }

    public function valor(Request $request)
    {
        $request->validate(['marca' => 'required', 'modelo' => 'required', 'ano' => 'required']);
        $res = Http::get("{$this->base}/marcas/{$request->marca}/modelos/{$request->modelo}/anos/{$request->ano}");
        return response()->json($res->json());
    }
}
