<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Follower;
use App\Models\FollowerRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function count(Request $request)
    {
        $accountId = $request->input('id');

        $count = Follower::where('account_id', '=', $accountId)
            ->get()
            ->count();

        return JsonResponse::success('Request has succeed', [
            'count' => $count,
        ]);
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

    public function accept(Request $request)
    {
        $requestId = $request->input('request_id');

        $followerRequest = FollowerRequest::where('id', $requestId)->first();

        $followerRequest->update(['is_accepted' => true]);

        Follower::create([
            'account_id' => $followerRequest->account_id,
            'followed_account_id' => $followerRequest->followed_account_id
        ]);

        return JsonResponse::success();
    }

    public function requests(Request $request)
    {
        $accountId = $request->input('account_id');

        $requests = FollowerRequest::where('followed_account_id', $accountId)->get();

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
