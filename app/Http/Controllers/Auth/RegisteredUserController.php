<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\SessionYear;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $batches=StudentBatch::all();
        $sessions = SessionYear::all();
        $departments=Department::where('name','!=','boat')->get();
        return Inertia::render('Auth/Register', [
            'batches' => $batches,
            'sessions' => $sessions,
            'departments' => $departments,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        request()->validate([
            "name" => "required",
            "email" => "required",
            "roll" => "required|numeric",
            "reg" => "required|unique:students,reg_no",
            "shift" => "required",
            "batch" => "required|exists:student_batches,id",
            "session" => "required|exists:session_years,id",
            "department" => "required|exists:departments,id",
            'email' => 'required|string|email|max:255|unique:students',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        Students::create([
            "name" => request("name"),
            "email" => request("email"),
            "roll" => request("roll"),
            "reg_no" => request("reg"),
            "shift" => request("shift"),
            "batch_id" => request("batch"),
            "department_id" => request("department"),
            "session_id" => request("session"),
            "password" => Hash::make(request("password")),
        ]);
        return back()->with('success', 'Student Created Successfully');
        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }
}
