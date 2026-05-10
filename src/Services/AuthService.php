<?php

declare(strict_types=1);

namespace Nank\Awalan\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @param array<string, string> $credentials
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
