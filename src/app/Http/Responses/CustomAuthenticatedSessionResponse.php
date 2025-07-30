<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticatedSessionResponse implements LoginResponse
{
    public function toResponse($request): Response
    {
        dd('CustomAuthenticatedSessionResponse is being executed.');
        $user = $request->user();

        if ($user && $user->profile_key === 1 && ! $user->address) {
            return redirect()->intended('/mypage/profile')->with('message', '住所を登録してください。');
        }

        return $request->wantsJson()
                    ? new JsonResponse(['two_factor' => false])
                    : redirect()->intended(config('fortify.home'));
    }
}
