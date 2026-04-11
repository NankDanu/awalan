<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param array<string, string> $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Log out the current user.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
