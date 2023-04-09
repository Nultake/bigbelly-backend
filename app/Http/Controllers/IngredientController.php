<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function all()
    {
        return JsonResponse::success('Request has succeed', [
            'ingredients' => Ingredient::all()->toArray()
        ]);
    }

    public function take($skip = 0, $take)
    {
        return JsonResponse::success('Request has succeed', [
            'ingredients' => Ingredient::skip($skip)->take($take)->get()->toArray()
        ]);
    }
}
