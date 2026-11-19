<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Menampilkan halaman permintaan tautan reset password.
    public function showLinkRequestForm()
    {
        return view('auth.forgotpassword');
    }

    // Mengirim tautan reset password ke email pengguna.
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        // Pesan dibuat sama agar tidak membocorkan apakah email terdaftar.
        return back()->with(
            'status',
            'Jika email terdaftar, tautan reset password akan dikirim.'
        );
    }

    // Menampilkan halaman untuk memasukkan password baru.
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.resetpassword', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Memproses perubahan password menggunakan token yang valid.
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login.');
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->withInput($request->only('email'));
    }
}
