<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCities(Request $request)
    {
        if ($request->has('name')) {
            $name = $request->input('name');
            $cities = City::where('name', 'like', $name . "%")
                          ->limit(10) 
                          ->get();
            return response()->json($cities);
        }
        return response()->json(['error' => 'Parâmetro "name" é obrigatório.'], 400);
    }
}
