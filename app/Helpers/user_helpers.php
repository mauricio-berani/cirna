<?php

use App\Models\Auth\User;

if (! function_exists('logged_user_id')) {
    function logged_user_id(): ?string
    {
        return auth()->guard('web')->user()?->id;
    }
}

if (! function_exists('logged_user')) {
    function logged_user(): User
    {
        return auth()->guard('web')->user();
    }
}
