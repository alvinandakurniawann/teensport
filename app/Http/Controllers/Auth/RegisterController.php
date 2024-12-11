<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); // Changed from regis to register
    }

    public function store(Request $request)
    {
        // Add your registration logic here
    }

    // ...existing code...
}