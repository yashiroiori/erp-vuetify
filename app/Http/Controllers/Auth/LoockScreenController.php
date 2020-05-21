<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoockScreenController extends Controller
{
    public function lock()
    {
        return Inertia::render('Pages/Auth/LockScreen');
    }

    public function unlock(Request $request)
    {
        $check = Hash::check($request->input('password'), $request->user()->password);
        if(!$check){
            return redirect()->back()->with('error', 'La contraseña no es correcta');
        }
        session(['lock-expires-at' => now()->addMinutes($request->user()->getLockoutTime())]);
        return redirect()->route('admin.dashboard');
    }
}
