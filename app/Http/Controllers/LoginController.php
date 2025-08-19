<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        //User::factory()->create();
        //Auth::loginUsingId(id: 1);
        return view(view: 'login.index');
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
