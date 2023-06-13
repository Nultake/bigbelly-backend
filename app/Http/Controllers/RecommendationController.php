<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Ingredient;
use App\Models\Post;
use App\Models\PostIngredient;
use App\Models\RecommendationCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function recommendation(Request $request)
    {
        $accountId = $request->input('account_id');

        $post = RecommendationCriteria::select(
            'post_ingredients.post_id',
            DB::raw('SUM(`recommendation_criterias`.`value`) as post_value')
        )
            ->join(
                'post_ingredients',
                'post_ingredients.ingredient_id',
                '=',
                'recommendation_criterias.ingredient_id'
            )
            ->where('recommendation_criterias.account_id', $accountId)
            ->groupBy('post_ingredients.post_id')
            ->orderBy('post_value', 'desc')
            ->first();

        if ($post === null)
            return JsonResponse::error('There is no enough information to recommend');



        return JsonResponse::success('Request has succeed', [
            'post' => Post::with([
                'ingredients',
                'ingredients.ingredient',
                'likes',
                'steps',
                'tags',
                'comments'
            ])->find($post->post_id)
        ]);
    }
}
