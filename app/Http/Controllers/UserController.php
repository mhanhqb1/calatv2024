<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('welcome'); // Trang chính dành riêng cho user
    }
}
