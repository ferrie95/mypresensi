<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidebarController extends Controller
{
    public function index()
    {
        // Ambil role pengguna yang sedang login
        $role = Auth::user()->role;

        // Ambil menu yang sesuai dengan role pengguna
        $menus = Menu::whereHas('roles', function($query) use ($role) {
            $query->where('role', $role);
        })->orderBy('order')->get();

        return view('dashboard', compact('menus'));
    }
}
