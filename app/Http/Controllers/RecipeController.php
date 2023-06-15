<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function recipe(Request $request, int $id)
    {
        Recipe::create([
            'account_id' => $request->input('account_id'),
            'post_id' => $id
        ]);

        return JsonResponse::success();
    }

    public function derecipe(Request $request, int $id)
    {
        Recipe::where([
            'account_id' => $request->input('account_id'),
            'post_id' => $id
        ])->delete();

        return JsonResponse::success();
    }

    public function getRecipes(Request $request)
    {
        $posts = Recipe::with([
            'post',
            'post.ingredients',
            'post.ingredients.ingredient',
            'post.likes',
            'post.steps',
            'post.tags',
            'post.comments',
            'post.institutional_post'
        ])->where('account_id', $request->input('account_id'))->get();

        return JsonResponse::success('Request has succeed!', [
            'posts' => $posts->toArray()
        ]);
    }
}