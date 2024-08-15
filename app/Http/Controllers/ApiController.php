<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{    
    // API para consulta o valor do Real e do Dolar
    public function getExchangeRates()
    {
        // Chave de autenticação obs:coloquei aqui pq em .env tava dando um erro.
        $apiKey = "9222914cffe73090bbb7fa9d";
        
        // Faz a consulta no endpoint da Exchange
        $response = Http::get("https://v6.exchangerate-api.com/v6/$apiKey/latest/USD");
        
        // Verifica se a resposta contém os dados esperados
        if ($response->ok()) {
            $data = $response->json();
            return response()->json([
                'usd_to_brl' => $data['conversion_rates']['BRL'] ?? null,
            ]);
        }

        return response()->json(['error' => 'Não foi possível obter taxas de câmbio'], 500);
    }
}
