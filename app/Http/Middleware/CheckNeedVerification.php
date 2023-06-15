<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse\JsonResponse;
use App\Helpers\VerificationGenerators\SixDigitGenerator;
use App\Models\Account;
use App\Models\AccountVerificationCode;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckNeedVerification
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
        $username = $request->input('username');


        $account = Account::where('username', $username)->first();

        if (!$account->is_verified) {
            $accountVerificationCode = AccountVerificationCode::where([
                'account_id' => $account->id,
                'is_used' => false
            ])->where('expired_at', '>', Carbon::now())
                ->get()
                ->first();
            $code = $accountVerificationCode === null ? SixDigitGenerator::generate() : $accountVerificationCode->code;
            if ($accountVerificationCode == null)
                AccountVerificationCode::create([
                    'code' => $code,
                    'account_id' => $account->id,
                    'expired_at' => Carbon::now()->addMinutes(10)
                ]);

            return JsonResponse::redirect('Account needs verification', [
                'id' => $account->id,
                'username' => $account->username,
                'privacy' => $account->privacy_setting->is_private,
                'email' => $account->email,
                'code' => $code,
            ]);
        }

        return $next($request);
    }
}