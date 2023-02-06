<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Helpers\VerificationCreators\SixDigitCreator;
use App\Helpers\VerificationGenerators\SixDigitGenerator;
use App\Models\Account;
use App\Models\AccountVerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        dd($request->input('id'));
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
}
