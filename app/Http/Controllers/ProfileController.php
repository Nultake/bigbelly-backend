<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use App\Models\Follower;
use App\Models\FollowerRequest;
use Illuminate\Http\Request;

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
        $followers = Account::find($id)->followers()->with('account')->get();

        return JsonResponse::success('Request has succeed!', [
            'followers' => $followers->toArray()
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
