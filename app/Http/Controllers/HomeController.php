<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'petani') {
            return redirect()->route('petani.dashboard');
        } elseif ($user->role === 'rice_mill') {
            return redirect()->route('ricemill.dashboard');
        } elseif ($user->role === 'packager') {
            return redirect()->route('packager.dashboard');
        }

        return view('home');
    }
}
