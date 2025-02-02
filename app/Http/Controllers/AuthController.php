<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ], [
    //         'email.required' => 'Email wajib diisi.',
    //         'email.email' => 'Format email tidak valid.',
    //         'password.required' => 'Password wajib diisi.',
    //     ]);
    
    //     // Ambil data login
    //     $credentials = $request->only('email', 'password');
    //     $remember = $request->boolean('remember'); // Laravel mendukung casting boolean otomatis
    
    //     // Coba login
    //     if (Auth::attempt($credentials, $remember)) {
    //         // Regenerate session untuk keamanan
    //         $request->session()->regenerate();
    
    //         // Redirect ke dashboard
    //         return response()->json([
    //             'success' => true,
    //             'redirect' => route('dashboard'),
    //         ], 200);
    //     }
    
    //     // Jika gagal login
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Email atau password salah.',
    //     ], 401);
    // }



    public function login(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric',
            'password' => 'required|string',
        ]);

        // Cari Employee berdasarkan NIK
        $employee = Employee::where('nik', $validated['nik'])->first();

        // Verifikasi password dan lakukan login
        if ($employee && Hash::check($validated['password'], $employee->password)) {
            Auth::guard('web')->login($employee); // Pastikan guard digunakan
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid NIP or Password'], 401);
    }

 
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // return redirect('/login');
        return view('auth.login');
    }
}
