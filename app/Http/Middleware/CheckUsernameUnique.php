<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use Closure;
use Illuminate\Http\Request;

class CheckUsernameUnique
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

        $account = Account::where('username', $username)
            ->get();


        if ($account->count() > 0)
            return JsonResponse::error('Username already exists');

        return $next($request);
    }
}
