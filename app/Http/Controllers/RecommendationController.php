<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Ingredient;
use App\Models\Post;
use App\Models\PostIngredient;
use App\Models\PostLike;
use App\Models\RecommendationCriteria;
use App\Models\RecommendationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function recommendation(Request $request)
    {
        $accountId = $request->input('account_id');

        $post_likes = PostLike::where('account_id', $accountId)->get();

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
            ->whereNotIn('post_ingredients.post_id', $post_likes->pluck('post_id'))
            ->groupBy('post_ingredients.post_id')
            ->orderBy('post_value', 'desc')
            ->first();

        if ($post === null)
            return JsonResponse::error('There is no enough information to recommend');

        RecommendationHistory::create([
            'account_id' => $accountId,
            'post_id' => $post->post_id
        ]);

        return JsonResponse::success('Request has succeed', [
            'post' => Post::with([
                'ingredients',
                'ingredients.ingredient',
                'likes',
                'steps',
                'tags',
                'comments',
                'account',
                'institutional_post'
            ])->find($post->post_id)
        ]);
    }

    public function groupRecommendation(Request $request)
    {
        $accountId = $request->input('account_id');

        $accounts = $request->input('accounts');

        $post_likes = PostLike::whereIn('account_id', $accounts)->get();

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
            ->whereIn('recommendation_criterias.account_id', $accounts)
            ->whereNotIn('post_ingredients.post_id', $post_likes->pluck('post_id'))
            ->groupBy('post_ingredients.post_id')
            ->orderBy('post_value', 'desc')
            ->first();

        if ($post === null)
            return JsonResponse::error('There is no enough information to recommend');

        RecommendationHistory::create([
            'account_id' => $accountId,
            'post_id' => $post->post_id
        ]);

        return JsonResponse::success('Request has succeed', [
            'post' => Post::with([
                'ingredients',
                'ingredients.ingredient',
                'likes',
                'steps',
                'tags',
                'comments',
                'account',
                'institutional_post'
            ])->find($post->post_id)
        ]);
    }

    public function history(Request $request)
    {
        dd(Post::with([
            'ingredients',
            'ingredients.ingredient',
            'likes',
            'steps',
            'tags',
            'comments',
            'account',
            'institutional_post'
        ])->where('account_id', $request->input('account_id'))
            ->get()
            ->toArray());
        return JsonResponse::success('Request has succeed', [
            'posts' => Post::with([
                'ingredients',
                'ingredients.ingredient',
                'likes',
                'steps',
                'tags',
                'comments',
                'account',
                'institutional_post'
            ])->where('account_id', $request->input('account_id'))
                ->get()
                ->toArray()
        ]);
    }
}
