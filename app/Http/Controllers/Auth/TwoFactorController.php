<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    
    public function index()
    {
        return view('auth.verify-2fa');
    }


    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $user = auth()->user(); 

        
        if ($request->code == $user->two_factor_code && now()->lessThan($user->two_factor_expires_at)) {
            
            
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

        
            return redirect()->route('dashboard'); 
        }

    
        return back()->withErrors(['code' => 'The provided code is invalid or has expired.']);
    }
}