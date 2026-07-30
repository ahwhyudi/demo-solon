<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function prosesLogin(Request $request)
    {
        // 1. Validasi cukup 'required' aja (JANGAN pakai aturan 'email' di sini)
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $encryptedEmail = $request->input('email');
        $encryptedPassword = $request->input('password');

        $privateKeyPath = storage_path('app/keys/private.pem');
        if (!file_exists($privateKeyPath)) {
            return redirect()->back()->withErrors(['email' => 'Sistem keamanan belum dikonfigurasi.']);
        }

        $privateKey = file_get_contents($privateKeyPath);
        $pkey = openssl_pkey_get_private($privateKey);

        // 2. Dekripsi Email
        $decryptedEmail = '';
        $successEmail = openssl_private_decrypt(
            base64_decode($encryptedEmail),
            $decryptedEmail,
            $pkey
        );

        // 3. Dekripsi Password
        $decryptedPassword = '';
        $successPassword = openssl_private_decrypt(
            base64_decode($encryptedPassword),
            $decryptedPassword,
            $pkey
        );

        // Kalau salah satu gagal dibuka gemboknya
        if (!$successEmail || !$successPassword || empty($decryptedEmail) || empty($decryptedPassword)) {
            return redirect()->back()->withErrors([
                'email' => 'Payload keamanan tidak valid atau rusak.'
            ]);
        }

        // (Opsional) Cek apakah hasil dekripsi email formatnya valid
        if (!filter_var($decryptedEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withErrors([
                'email' => 'Format email tidak valid.'
            ]);
        }

        // 4. Masukkan email & password asli yang udah bersih ke Auth Laravel
        $credentials = [
            'email' => $decryptedEmail,
            'password' => $decryptedPassword
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}