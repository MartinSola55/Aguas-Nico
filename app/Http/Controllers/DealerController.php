<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */    
    public function index()
    {
        $users = User::where('rol_id', 2)->get();
        return view('dealers.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dealer = User::find($id);
        return view('dealers.details', compact('dealer'));
    }
}
