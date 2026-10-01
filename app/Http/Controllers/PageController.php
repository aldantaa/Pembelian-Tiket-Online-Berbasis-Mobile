<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        return redirect()->route('schedules');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login!');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }

    public function schedules()
    {
        return view('booking.schedules');
    }

    public function tickets()
    {
        return view('tickets.index');
    }

    public function showTicket($id)
    {
        return view('tickets.show', compact('id'));
    }

    public function profile()
    {
        return view('auth.profile');
    }
}
