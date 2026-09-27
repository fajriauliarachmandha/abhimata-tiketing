<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    // menampilkan halaman login
    public function create(){
        return Inertia::render('Auth/Login');
    }

    // proses login
    public function store(Request $request){
        $credentials = $request->validate([
            'username'=>'required|string',
            'password'=>'required|string',
        ]);

        if (!Auth::attempt($credentials)){
            return back()->withErrors([
                'username'=>'Username atau password salah!',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return $user->role==='it_support'?redirect()->route('admin.home'):redirect()->route('tickets.create');
    }

    public function destroy(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
