<?php

namespace App\Http\Controllers;

use App\Models\CourseAssign;
use App\Models\ExamAssign;
use App\Models\Marks;
use App\Models\Students;
use App\Models\TeacherAssignCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExamAssignController extends Controller
{
    public function makeExam($id)
    {
        $tcourse = TeacherAssignCourse::findOrFail($id);
        $copos = CourseAssign::where('course_id', $tcourse->course_id)
            ->with('relCo', 'relPo')->get();
        $exams = ExamAssign::where('t_assign_courses_id', $id)->with('relCo', 'relPo', 'relBatch')->get();
        return Inertia::render('TeacherPages/ExamMake', [
            'copos' => $copos,
            'tcourse' => $tcourse,
            'exams' => $exams,
        ]);
    }

    public function makeExamStore(Request $req, $id)
    {
        $req->validate([
            'copo' => 'required',
            'marks' => 'required',
            'name'  => 'required'
        ]);
        $tcourse = TeacherAssignCourse::findOrFail($id);
        $copos = CourseAssign::where('id', request('copo'))->with('relCo', 'relPo')->firstOrFail();
        ExamAssign::create([
            'course_id' => $tcourse->course_id,
            't_assign_courses_id' => $id,
            'teacher_id' => auth()->id(),
            'co_id' => $copos->relCo->id,
            'po_id' => $copos->relPo->id,
            'copo_id' => request('copo'),
            'marks' => request('marks'),
            'name' => request('name'),
        ]);
        return back()->with('success', 'Exam Created Successfully');
    }

    public function markCreate(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);
        $examAssigns = ExamAssign::where('t_assign_courses_id', $id)->get();
        $students = Students::where('batch_id', $teacherAssigns->batch_id)->get();
        return Inertia::render('TeacherPages/Marks', [
            'examAssigns' => $examAssigns,
            'students' => $students,
            'teacherAssigns' => $teacherAssigns
        ]);
    }
    public function markStore(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);
        //dd($req->all());
        try {
            DB::transaction(function () use ($req, $teacherAssigns,$id) {
                foreach ($req->all() as $value) {
                    $s_id = $value['student_id'];
                    $roll = $value['student_roll'];
                    foreach ($value['marks'] as $mark) {
                        Marks::create([
                            'student_id' => $s_id,
                            'batch_id' => $teacherAssigns->batch_id,
                            'roll' => $roll,
                            'course_id' => $teacherAssigns->course_id,
                            'copo_id' => 1,
                            'co_id' => $mark['co_id'],
                            'po_id' => $mark['po_id'],
                            't_assign_courses_id' => $id,
                            'teacher_id' => $teacherAssigns->user_id,
                            'exam_id' => $mark['exam_id'],
                            'marks' => $mark['mark'],
                            'p_marks' => ($mark['mark'] / $mark['total']) * 100,
                        ]);
                    };
                }
            });
            return back()->with('success', 'Mark Submitted Successfully');

        } catch (\Exception $e) {
            info("$e->getMessage() in $e->getFile() line $e->getLine()");
            throw $e;
        }


    }
}
