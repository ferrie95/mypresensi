<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiOnlineController extends Controller
{
    public function index()
    {
        return view('absensi.absensi_online.index');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        return redirect()->route('absensi_online.index');
    }
    
}
