<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function show()
    {
        try {
            if (Auth::check()) {
                return redirect('/dashboard');
            }
            return view('page.login');
        } catch (Exception $e) {
            return back()->with('error', 'An unexpected error occurred.');
        }
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],  
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard')
                    ->with('message', 'You are now logged in!');
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->except('password'));
        } catch (Exception $e) {
            return back()->with('error', 'Login failed. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('message', 'You have been logged out!');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Logout failed. Please try again.');
        }
    }
}