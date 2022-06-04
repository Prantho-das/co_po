<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::paginate()
        ]);
    }
    public function create()
    {
        return Inertia::render('Users/Create');
    }
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'department'=>'required',
            'designation'=>'nullable|array',
            'phone'=>'required|digits:11',
            'role_type'=>'required',
            'avatar'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $filePath=null;
        if(request()->hasFile('avatar')){
            $fileName= request()->file('avatar')->hashName();
            request()->file('avatar')->move(public_path('images/avatar'),$fileName);
            $filePath=env('APP_URL')."images/avatar/".$fileName;
        }
        User::create([
            'name' =>request('name'),
            'email' =>request('email'),
            'password' => Hash::make(request('password')),
            'department' => request('department'),
            'designation' => request('designation')?json_encode(request('designation')) : null,
            'address'=>request()->address ?? null,
            'phone' => request()->phone,
            'role' => request()->role_type,
            'profile_image' => $filePath,
        ]);

        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }
}
