<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        if ($user->rol_id == '1') {
            $dealer = User::find($id);
            return view('dealers.details', compact('dealer'));
        } else {
            $dealer = User::find(auth()->user()->id);
            return view('dealers.details', compact('dealer'));
        }
    }
}
