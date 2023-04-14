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
            'account_id' => $request->input('account_id'),
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

        $postId = Post::create($post)->id;

        $createPostTags = [];
        $createPostIngredients = [];
        $createPostSteps = [];

        foreach ($postTags as $tag) {
            $tab['post_id'] = $postId;

            $createPostTags[] = $tag;
        }

        foreach ($postSteps as $step) {
            $step['post_id'] = $postId;

            $createPostSteps[] = $step;
        }

        foreach ($postIngredients as $ingredient) {
            $ingredient['post_id'] = $postId;

            $createPostIngredients[] = $ingredient;
        }


        PostTag::insert($createPostTags);

        PostStep::insert($createPostSteps);

        PostIngredient::insert($createPostIngredients);

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
