<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user && $user->role === 'treasurer') {
            return view('dashboard.treasurer');
        }

        return view('dashboard.member');
    }
}
