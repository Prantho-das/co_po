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
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseSemester($semester){
       return Course::where('semester_id',$semester)->get();
    }
    public function index()
    {

        $courses = Course::withCount('relcopoAssign as copoAssign')->with('relcopoAssign.relCo', 'relcopoAssign.relPo')->paginate();
        $semesters = Semester::all();
        $cos = CourseOutcome::all();
        return Inertia::render("Course/Index", ["courses" => $courses, "semesters" => $semesters, "cos" => $cos]);
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
            'credit' => 'required|numeric|min:1',
            // 'co' => 'required|array',
        ]);
        // $course=Course::where('c_code', $req->code)->where('semester_id',$req->semester)->first();
        // if($course) {
        //     return back()->withErrors('Course', 'Course Already Exists In One Semester');
        // }
        $semester = Semester::findOrFail($req->semester);
        $course = Course::create([
            'c_name' => $req->name,
            'c_code' => $req->code,
            'semester_id' => $req->semester,
            'semester_name' => $semester->name,
            'credit' => $req->credit,
        ]);
        // foreach (request()->co as $val) {
        //     CourseAssign::create([
        //         'course_id' => $course->id,
        //         'co_id' => $val,
        //     ]);
        // }
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
        $pos = ProgramOutcome::all();
        return Inertia::render("Course/AssignCoPo", ["course" => $course, 'pos' => $pos, 'assigns' => $assigns]);
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

        $req->validate([
            'course' => 'required|exists:courses,id',
            'co' => 'required|exists:course_outcomes,id',
            'po' => 'required|exists:program_outcomes,id'
        ]);
        $coValidate = CourseAssign::where('course_id', $req->course)->where('co_id', $req->co)->whereNot('po_id',null)->exists();
        //  $copoValidate = CourseAssign::where('course_id', $req->course)->where('co_id', $req->co)->where('po_id', $req->po)->exists();
        if ($coValidate) {
            return back()->withErrors(['co' => 'Co Already Assign']);
        }
        // if ($copoValidate) {
        //     return back()->withErrors(['co' => 'One Po Is For One Po & Its Already Assign']);
        // }
        CourseAssign::where('course_id', $req->course)
            ->where('co_id', $req->co)->firstOrFail()->update([
                'po_id' => $req->po
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
        $teacherAssign = TeacherAssignCourse::where('course_id', $req->course)
            ->where('user_id', $req->teacher)
            ->where('batch_id', $req->batch)
            ->exists();
        if ($teacherAssign) {
            return back()->withErrors(['teacher' => 'Teacher Already Assign To This Course']);
        }
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
        $running =   TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->where('user_id', auth()->id())->whereNull('course_complete_at')->withCount('relExam')->paginate(10);
        return Inertia::render("TeacherPages/AssignTeacher", ['running' => $running]);
    }
    public function myCompletedCourse()
    {
        $completed = TeacherAssignCourse::with("relTeacher", "relSemester", "relBatch", 'relSession', 'relCourse')->where('user_id', auth()->id())->whereNotNull('course_complete_at')->withCount('relExam')->paginate(10);
        return Inertia::render("TeacherPages/TeacherCompletedCourse", ['completed' => $completed]);
    }
    public function completeCourse($id){
        $tCourse=TeacherAssignCourse::findOrFail($id);
        $tCourse->course_complete_at=now();
        $tCourse->save();
        return back()->with('success', 'Course Completed Successfully');
    }

}
