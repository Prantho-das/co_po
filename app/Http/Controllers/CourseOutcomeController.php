<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\CourseOutcome;
use App\Models\ProgramOutcome;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseOutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cos = CourseOutcome::paginate();
        $subjects = Course::all();
        $pos = ProgramOutcome::all();
        return Inertia::render('Co/Index', [
            'cos' => $cos,
            'courses' => $subjects,
            'pos' => $pos,
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
        $request->validate([
            'name' => "required|unique:course_outcomes,co_name|min:1",
            'course' => "required|exists:courses,id",
            "po" => "required|exists:program_outcomes,id",
            "co_position" => "required",
        ]);
        $co =  CourseOutcome::create([
            'co_name' => $request->name,
            'co_no' => $request->co_position,
        ]);
        $coValidate = CourseAssign::where('course_id', $request->course)->where('co_id', $co->id)->where('po_id', $request->po)->exists();
        //  $copoValidate = CourseAssign::where('course_id', $req->course)->where('co_id', $req->co)->where('po_id', $req->po)->exists();
        if ($coValidate) {
            return back()->withErrors(['co' => 'Co Already Assign']);
        }
        // if ($copoValidate) {
        //     return back()->withErrors(['co' => 'One Po Is For One Po & Its Already Assign']);
        // }
            CourseAssign::create([
                'course_id' => $request->course,
                'co_id' => $co->id,
                'po_id' => $request->po,
            ]);


        return back()->with('success', 'Course Outcome Created Successfully');
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
