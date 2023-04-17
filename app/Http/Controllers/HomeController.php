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
        $request = new Request(['start_daytime' => now()->toDateString()]);
        $response = app(RouteController::class)->show($request);
        $routes = $response->getData()->routes;
        return view('home', compact('routes'));
    }
}
