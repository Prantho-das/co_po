<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Models\Department;
use App\Models\SessionYear;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

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
        $departments = Department::all();
        return Inertia::render('Users/Create', [
            'departments' => $departments
        ]);
    }
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'department' => 'required|exists:departments,id',
            'designation' => 'nullable|array',
            'phone' => 'required|digits:11',
            'role_type' => 'required',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $department = Department::find(request('department'));
        $filePath = null;
        if (request()->hasFile('avatar')) {
            $fileName = request()->file('avatar')->hashName();
            request()->file('avatar')->move(public_path('images/avatar'), $fileName);
            $filePath = env('APP_URL') . "images/avatar/" . $fileName;
        }
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'department' => $department->name,
            'department_id' => request('department'),
            'designation' => request('designation') ? json_encode(request('designation')) : null,
            'address' => request()->address ?? null,
            'phone' => request()->phone,
            'role' => request()->role_type,
            'profile_image' => $filePath,
        ]);

        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }





    // students



    public function studentShow()
    {
        Students::with('relSession', 'relBatch')->paginate();
        return Inertia::render('Student/Index', [
            'students' => Students::with('relSession', 'relBatch')->paginate()
        ]);
    }
    public function studentCreate()
    {
        $batches = StudentBatch::all();
        $sessions = SessionYear::all();
        $departments = Department::all();
        return Inertia::render('Student/Create', [
            'batches' => $batches,
            'sessions' => $sessions,
            'departments' => $departments,
        ]);
    }
    public function studentStore()
    {
        request()->validate([
            "name" => "required",
            "email" => "required",
            "roll" => "required|numeric",
            "reg" => "required",
            "shift" => "required",
            "batch" => "required",
            "session" => "required",
            "department" => "required",
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
            "password" => Hash::make(request("email")),
        ]);
        return redirect()->route('users.studentShow')->with('success', 'Student Created Successfully');
    }

    public function studentBulk()
    {

        request()->validate([
            'student_csv' => 'required|mimes:csv',
            'department' => 'required|exists:departments,id',
            'batch' => 'required|exists:student_batches,id',
            'session' => 'required|exists:session_years,id',
            'shift' => 'required',
        ]);
        try {
            DB::transaction(function () {
                $file = request()->file('student_csv');
                Excel::import(new StudentImport(request('batch'),request('session'),request('department'), request('shift')), $file);
            });
            return back()->with('success', 'Students Imported Successfully');
        } catch (\Exception $e) {
            throw $e;
            return redirect('/')->with('error', 'Some Problem Occured');
        }
    }
    public function studentBatchInfo($departmentId)
    {
        $batches = StudentBatch::where('department_id', $departmentId)->get();
        return response($batches);
    }
    public function studentInfoByBatch($batchId)
    {
        $students = Students::where('batch_id', $batchId)->select('id','name','roll')->get();
        return response($students);
    }
}
