<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\SessionYear;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = StudentBatch::with('relSession')->paginate();
        $sessions = SessionYear::all();
        $departments = Department::whereNot('name', 'boat')->get();

        return Inertia::render('Batch/Index', [
            'batches' => $batches,
            'sessions' => $sessions,
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //dd($request->all());
        $request->validate([
            'session' => "required|exists:session_years,id",
            'department' => "required|exists:departments,id",
            'batch_name' => "required|min:1|max:12",
            'semester'=> "required|numeric|min:1|max:12",
        ]);
        $uniquevalidation=StudentBatch::where('session_id',$request->session)->where('department_id',$request->department)->where('name',$request->batch_name)->exists();
        if($uniquevalidation){
            return back()->withErrors(['batch_name'=>'Batch Already Exists']);
        }
        StudentBatch::create([
            'session_id' => $request->session,
            'department_id' => $request->department,
            'name' => $request->batch_name,
            'total_semester' => $request->semester,
        ]);

        return back()->with('success', 'Batch Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
