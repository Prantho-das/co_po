<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\CourseOutcome;
use App\Models\ProgramOutcome;
use App\Models\Semester;
use App\Models\SessionYear;
use App\Models\StudentBatch;
use App\Models\TeacherAssignCourse;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $courses = Course::paginate();
        $semesters = Semester::all();
        return Inertia::render("Course/Index", ["courses" => $courses, "semesters" => $semesters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'code' => 'required|unique:courses,c_code',
            'semester' => 'required|exists:semesters,id',
            'credit' => 'required|numeric'
        ]);
        $semester = Semester::findOrFail($req->semester);
        Course::create([
            'c_name' => $req->name,
            'c_code' => $req->code,
            'semester' => $semester->name,
            'credit' => $req->credit,
        ]);
        return back()->with("success", "Course Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        $assigns = CourseAssign::where('course_id', $id)->with("relCo", "relPo")->get();
        $cos = CourseOutcome::all();
        $pos = ProgramOutcome::all();
        return Inertia::render("Course/AssignCoPo", ["course" => $course, 'cos' => $cos, 'pos' => $pos, 'assigns' => $assigns]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    public function assignCoPo(Request $req)
    {

        $coValidate = CourseAssign::where('course_id', $req->course)->where('co_id', $req->co)->exists();
        if ($coValidate) {
            return back()->withErrors(['co' => 'Co Already Assign']);
        }
        $req->validate([
            'course' => 'required|exists:courses,id',
            'co' => 'required|exists:course_outcomes,id',
            'po' => 'required|exists:program_outcomes,id'
        ]);
        CourseAssign::create([
            "course_id" => $req->course,
            "co_id" => $req->co,
            "po_id" => $req->po
        ]);
        return back()->with('success', 'Course Assign Successfully');
    }

    public function assignTeacherShow(Request $req)
    {
        $completed = TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->whereNotNull('course_complete_at')->paginate(10);
        $running = TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->whereNull('course_complete_at')->paginate(10);
        return Inertia::render("Course/AssignTeacher", ['completed' => $completed, 'running' => $running]);
    }
    public function assignTeacherCreate(Request $req)
    {
        $teachers = User::where('role', "TEACHER")->get();
        $courses = Course::all();
        $semesters = Semester::all();
        $batches = StudentBatch::whereNull('batch_end_at')->get();
        $sessions = SessionYear::all();

        return Inertia::render("Course/AssignTeacherStore", ['teachers' => $teachers, 'courses' => $courses, 'semesters' => $semesters, 'batches' => $batches, 'sessions' => $sessions]);
    }
    public function assignTeacherStore(Request $req)
    {
        $req->validate([
            'course'    => 'required|exists:courses,id',
            'teacher'      => 'required|exists:users,id',
            'semester'  => 'required|exists:semesters,id',
            'session'   => 'required|exists:session_years,id',
            'batch'     => 'required|exists:student_batches,id',
        ]);
        TeacherAssignCourse::create([
            'course_id' => $req->course,
            'user_id' => $req->teacher,
            'batch_id' => $req->batch,
            'semester_id' => $req->semester,
            'session_id' => $req->session,
        ]);
        return back()->with('success', 'Course Assign Successfully');
    }







    // teacher
    public function myCourse()
    {
        $completed = TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->where('user_id', auth()->id())->whereNotNull('course_complete_at')->withCount('relExam')->paginate(10);
        $running =   TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->where('user_id', auth()->id())->whereNull('course_complete_at')->withCount('relExam')->paginate(10);
        return Inertia::render("TeacherPages/AssignTeacher", ['completed' => $completed, 'running' => $running]);
    }
}
