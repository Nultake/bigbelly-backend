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
use Response;
use Illuminate\Support\Facades\Storage;

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

        $postIngredients = $request->input('ingredients.*');

        $postTags = $request->input('tags.*');

        $postId = Post::create($post)->id;

        $createPostTags = [];
        $createPostIngredients = [];
        $createPostSteps = [];

        foreach ($postTags as $tag) {
            $tag['post_id'] = $postId;

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

        return JsonResponse::success('Request has succeed', [
            'post_id' => $postId
        ]);
    }

    public function like(Request $request)
    {
        PostLike::create($request->all());

        return JsonResponse::success();
    }

    public function unlike(Request $request)
    {
        $accountId = $request->input('account_id');
        $postId = $request->input('post_id');

        PostLike::where('account_id', $accountId)
            ->where('post_id', $postId)
            ->first()
            ->delete();

        return JsonResponse::success();
    }

    public function comment(Request $request)
    {
        PostComment::create($request->all());

        return JsonResponse::success();
    }

    public function getComments(Request $request, int $id)
    {
        $comments = Post::find($id)->comments()->with('account')->get();

        return JsonResponse::success('Request has succeed', [
            'comments' => $comments->toArray()
        ]);
    }

    public function addImage(Request $request, int $id)
    {
        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();

        $fullFileName = $id . '.' . $extension;
        $file->storeAs('uploads', $fullFileName,  ['disk' => 'local']);

        return JsonResponse::success();
    }

    public function getImage(Request $request, int $id)
    {

        $file = Storage::get('uploads/' . $id . '.jpg');
        $type = Storage::mimeType($file);
        $response = Response::make($file, 200)->header("Content-Type", $type);

        return $response;
    }

    public function archive(Request $request, int $id)
    {
        Post::find($id)->update(['is_archived' => true]);

        return JsonResponse::success();
    }

    public function dearchive(Request $request, int $id)
    {
        Post::find($id)->update(['is_archived' => true]);

        return JsonResponse::success();
    }
}
