<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use App\Models\Follower;
use App\Models\FollowerRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function info(Request $request, $id)
    {
        $user = Account::with(['privacy_setting', 'followers.account', 'followeds.followed_account'])->find($id);

        return JsonResponse::success('Request has succeed!', [
            'user' => $user->toArray()
        ]);
    }

    public function search(Request $request)
    {
        $user = Account::with(['privacy_setting', 'followers.account', 'followeds.followed_account'])
            ->where('username', $request->input('username'))
            ->first();

        if ($user === null)
            return JsonResponse::error('Username could not found');

        return JsonResponse::success('Request has succeed!', [
            'user' => $user->toArray()
        ]);
    }

    public function followers(Request $request, $id)
    {
        $followers = Account::find($id)->followers()->get();

        return JsonResponse::success('Request has succeed!', [
            'followers' => $followers->toArray()
        ]);
    }
    public function posts(Request $request, $id)
    {
        /**
         * @var Account
         */
        $account = Account::findOrFail($id);

        $posts = $account->posts()->with([
            'ingredients',
            'ingredients.ingredient',
            'likes',
            'steps',
            'tags',
            'comments'
        ])->get();

        return JsonResponse::success('Request has succeed!', [
            'account' => $account->toArray(),
            'posts' => $posts->toArray()
        ]);
    }

    public function homePagePosts(Request $request, $id)
    {

        $skip = $request->input('skip');
        $take = $request->input('take');

        $followeds = Account::find($id)->followeds()->with('followed_account')->get();


        $followedIdList = [];

        foreach ($followeds->toArray()['followed_account'] as $value)
            $followedIdList[] = $value['id'];



        $posts = Post::with([
            'account',
            'ingredients',
            'ingredients.ingredient',
            'likes',
            'steps',
            'tags',
            'comments'
        ])->whereIn('account_id', $followedIdList)
            ->where('is_archived', false)
            ->orderByDesc('created_at')
            ->skip($skip)
            ->take($take)
            ->get();

        return JsonResponse::success('Request has succeed!', [
            'posts' => $posts->toArray()
        ]);
    }

    public function followeds(Request $request, $id)
    {
        $followeds = Account::find($id)->followeds()->with('followed_account')->get();

        return JsonResponse::success('Request has succeed!', [
            'followeds' => $followeds->toArray()
        ]);
    }

    public function edit(Request $request, $id)
    {
        $privacySetting = $request->input('privacy_setting');

        $currentPrivacy = Account::find($id)->privacy_setting;

        $currentPrivacy->update(['is_private' => $privacySetting]);

        return JsonResponse::success();
    }

    public function editProfile(Request $request, $id)
    {
        $name = $request->input('name');
        // $surname = $request->input('surname');
        $password = $request->input('password');
        $old_password = $request->input('old_password');

        $account = Account::find($id);
        if (Hash::check($old_password, $account->password)) {
            $update_details = [
                'name' => $name,
                'password' => $password
            ];

            $account->update($update_details);

            return JsonResponse::success();
        }
        return JsonResponse::error();
    }

    public function follow(Request $request)
    {
        $accountId = $request->input('account_id');

        $followedAccountId = $request->input('followed_account_id');

        Follower::create([
            'account_id' => $accountId,
            'followed_account_id' => $followedAccountId
        ]);

        return JsonResponse::success();
    }

    public function unfollow(Request $request)
    {
        $accountId = $request->input('account_id');

        $followedAccountId = $request->input('followed_account_id');

        Follower::where([
            'account_id' => $accountId,
            'followed_account_id' => $followedAccountId
        ])->delete();

        return JsonResponse::success();
    }

    public function cancelFollowRequest(Request $request)
    {
        $accountId = $request->input('account_id');

        $followedAccountId = $request->input('followed_account_id');

        FollowerRequest::where([
            'account_id' => $accountId,
            'followed_account_id' => $followedAccountId
        ])->delete();

        return JsonResponse::success();
    }

    public function accept(Request $request)
    {
        $requestId = $request->input('request_id');

        $followerRequest = FollowerRequest::where('id', $requestId)->first();

        $followerRequest->delete();

        Follower::create([
            'account_id' => $followerRequest->account_id,
            'followed_account_id' => $followerRequest->followed_account_id
        ]);

        return JsonResponse::success();
    }

    public function requests(Request $request, $id)
    {

        $requests = FollowerRequest::where('followed_account_id', $id)->get();

        return JsonResponse::success('Request has succeed!', [
            'requests' => $requests->toArray()
        ]);
    }

    public function decline(Request $request)
    {
        $requestId = $request->input('request_id');

        $followerRequest = FollowerRequest::where('id', $requestId)->first();

        $followerRequest->delete();

        return JsonResponse::success();
    }
}
