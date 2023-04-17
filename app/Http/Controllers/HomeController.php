<?php

namespace App\Http\Controllers;

use App\Models\Route;
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
        $routes = Route::whereDate('start_daytime', now()->toDateString())->get();
        return view('home', compact('routes'));
    }
}
