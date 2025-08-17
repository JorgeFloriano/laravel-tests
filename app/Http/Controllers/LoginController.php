<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view(view: 'login.index', data:[
            'name' => $name
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $authenticated = Auth::attempt(credentials:$request->only(keys: ['email', 'password']));

        if (!$authenticated) {
            return redirect()->route(route: 'login')->with(['message' => 'The email or password is invalid']);
        }

        return redirect()->route(route: 'home');
    }
}
