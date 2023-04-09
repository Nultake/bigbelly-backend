<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostIngredient;
use App\Models\PostLike;
use App\Models\PostStep;
use App\Models\PostTag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $post = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'portion' => $request->input('portion'),
            'preparation_time' => $request->input('preparation_time'),
            'baking_time' => $request->input('baking_time'),
        ];

        $postSteps = $request->input('steps');

        $postIngredients = $request->input('ingredients');

        $postTags = $request->input('tags');

        Post::create($post);

        PostTag::insert($postTags);

        PostStep::insert($postSteps);

        PostIngredient::insert($postIngredients);

        return JsonResponse::success();
    }

    public function like(Request $request)
    {
        PostLike::create($request->all());

        return JsonResponse::success();
    }

    public function comment(Request $request)
    {
        PostComment::create($request->all());

        return JsonResponse::success();
    }
}
