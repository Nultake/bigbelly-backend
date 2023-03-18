<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Helpers\VerificationGenerators\SixDigitGenerator;
use App\Models\Account;
use App\Models\AccountVerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /*
        $payload = {
            username :
            password:
        }
    */
    public function login(Request $request)
    {
        $username = $request->input('username');

        $account = Account::where('username', $username)->first();

        return JsonResponse::success('Login successful', [
            'id' => $account->id,
            'username' => $account->username
        ]);
    }


    /*
        $payload = {
            username :
            name :
            email :
            password:
        }
    */
    public function register(Request $request)
    {
        $payload = $request->all();

        $payload['password'] = Hash::make($payload['password']);

        $account = Account::create($payload);

        AccountVerificationCode::create([
            'code' =>  SixDigitGenerator::generate(),
            'account_id' => $account->id,
            'expired_at' => Carbon::now()->addMinutes(10)
        ]);

        return JsonResponse::success();
    }
    /*
        $payload = {
            id :
            code :
        }
    */
    public function verificate(Request $request)
    {
        $verificationCode = $request->input('code');
        $accountID = $request->input('id');

        $accountVerificationCode = AccountVerificationCode::where([
            'account_id' => $accountID,
            'is_used' => false
        ])->where('expired_at', '>', Carbon::now())
        ->get()
        ->first();

        if ($accountVerificationCode->code == null)
            return JsonResponse::error('Verification code expired!');

        if ($verificationCode != $accountVerificationCode->code)
            return JsonResponse::error('Verification code is incorrect!');

        Account::where('id', $accountID)
            ->update(['is_verified' => true]);

        $accountVerificationCode->update(['is_used' => true]);

        return JsonResonse::success('Verification has done');
    }
}
