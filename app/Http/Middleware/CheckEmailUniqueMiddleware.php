<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\Account;
use Closure;
use Illuminate\Http\Request;

class CheckEmailUniqueMiddleware
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
        $email = $request->input('email');

        $account = Account::where('email', $email)
            ->get();

        if ($account->count() > 0)
            return JsonResponse::error('Email already exists');

        return $next($request);
    }
}
