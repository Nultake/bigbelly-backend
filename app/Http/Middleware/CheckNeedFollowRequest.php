<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use App\Models\AccountPrivacySetting;
use App\Models\FollowerRequest;
use Closure;
use Illuminate\Http\Request;

class CheckNeedFollowRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $accountId = $request->input('account_id');

        $followedAccountId = $request->input('followed_account_id');

        $accountPrivacy = AccountPrivacySetting::where('account_id', $followedAccountId)
            ->first()
            ->is_private;

        if ($accountPrivacy) {
            FollowerRequest::create([
                'account_id' => $accountId,
                'followed_account_id' => $followedAccountId
            ]);

            return JsonResponse::success('Request needs to be Accepted');
        }

        return $next($request);
    }
}
