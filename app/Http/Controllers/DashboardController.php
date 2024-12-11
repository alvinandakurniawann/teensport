<?php
// app/Http/Controllers/DashboardController.php
<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function userDashboard()
    {
        return view('user.dashboard');
    }
}