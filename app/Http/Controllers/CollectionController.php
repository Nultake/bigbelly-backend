<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Collection;
use App\Models\CollectionPost;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function create(Request $request)
    {
        Collection::create([
            'name' => $request->input('name'),
            'account_id' => $request->input('account_id')
        ]);

        return JsonResponse::success();
    }

    public function delete(Request $request, int $id)
    {
        Collection::find($id)->delete();

        return JsonResponse::success();
    }

    public function get(Request $request)
    {
        $collections = Collection::where('account_id', $request->input('account_id'))->get();

        return JsonResponse::success('Request has succeed', [
            'collections' => $collections->toArray()
        ]);
    }

    public function getPosts(Request $request, int $id)
    {
        $posts = Collection::with([
            'posts',
            'posts.ingredients',
            'posts.tags',
            'posts.account',
            'posts.steps',
            'posts.comments',
            'posts.likes',
            'posts.institutional_post'
        ])->find($id)->posts;

        return JsonResponse::success('Request has succeed', [
            'posts' => $posts
        ]);
    }

    public function addPost(Request $request, int $id)
    {
        CollectionPost::create([
            'collection_id' => $id,
            'post_id' => $request->input('post_id'),
        ]);


        return JsonResponse::success();
    }

    public function deletePost(Request $request, int $id)
    {
        CollectionPost::where([
            'collection_id' => $id,
            'post_id' => $request->input('post_id'),
        ])->delete();


        return JsonResponse::success();
    }
}