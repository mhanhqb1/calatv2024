<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập cho admin
    public function showAdminLoginForm()
    {
        return view('auth.admin_login');
    }

    // Xử lý đăng nhập cho admin
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Admins only.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Hiển thị form đăng nhập cho user
    public function showUserLoginForm()
    {
        return view('auth.user_login');
    }

    // Xử lý đăng nhập cho user
    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'user') {
                return redirect()->route('user.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Users only.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký user
    public function register(Request $request)
    {
        // Xác thực dữ liệu
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Tạo tài khoản user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::USER,
        ]);

        // Đăng nhập ngay sau khi đăng ký
        auth()->login($user);

        // Chuyển hướng đến trang user dashboard
        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
