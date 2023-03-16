<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckPasswordMatched
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

        $password = $request->input('password');

        if (!Hash::check($password, $account->password))
            return JsonResponse::error('Password is incorrect!');

        return $next($request);
    }
}
