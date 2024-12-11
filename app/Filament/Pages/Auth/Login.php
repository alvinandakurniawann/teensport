<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Session;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        Session::regenerate();
        
        request()->validate([
            '_token' => 'required',
        ]);

        $response = parent::authenticate();
        
        if ($response) {
            Session::put('auth.password_confirmed_at', time());
        }
        
        return $response;
    }
}