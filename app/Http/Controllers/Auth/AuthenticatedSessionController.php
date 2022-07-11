<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Students;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Ramsey\Uuid\Type\Integer;

use function PHPUnit\Framework\returnSelf;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function loginShow()
    {
        return Inertia::render('Auth/StudentLogin', [
            'canResetPassword' => false,
            'status' => session('status'),
        ]);
    }

    public function studentLogin()
    {
        request()->validate([
            'reg' => 'required|exists:students,reg_no'
        ], [
            'reg' => 'Registration No Not Found'
        ]);

        $student = Students::where('reg_no', request('reg'))->first();
        if (!$student) {
            return back()->withErrors(['reg' => 'Credential Not Matched']);
        }
        Auth::guard('student')->login($student);
        
        return redirect()->intended(RouteServiceProvider::STUDENT_HOME);
    }
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function studentDestroy()
    {
        Auth::guard('student')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }
}
