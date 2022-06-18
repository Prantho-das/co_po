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
use phpDocumentor\Reflection\DocBlock\Tags\Example;

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
        $examAssigns = ExamAssign::where('t_assign_courses_id', $id)->whereNull('mark_assign_done')->get();
        if (count($examAssigns) == 0) {
            return back()->with('error', 'Mark Assign Done');
        }
        if(count($examAssigns) == 0){
            return back()->with('error', 'No Exam Assigned');
        }
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
        // $req->validate([

        // ]);
        try {
            DB::transaction(function () use ($req, $teacherAssigns,$id) {
                foreach ($req->all() as $value) {
                    $s_id = $value['student_id'];
                    $roll = $value['student_roll'];
                    foreach ($value['marks'] as $mark) {
                        $examInfo=ExamAssign::findOrFail($mark['exam_id']);
                        if($examInfo->marks < 0 || (int)$mark['mark']> $examInfo->marks ){
                            return back()->with('error', 'Marks must be between 0 and total marks');
                        }
                        Marks::create([
                            'student_id' => $s_id,
                            'batch_id' => $teacherAssigns->batch_id,
                            'roll' => $roll,
                            'course_id' => $teacherAssigns->course_id,
                            'copo_id' => $mark['copo_id'],
                            'co_id' => $mark['co_id'],
                            'po_id' => $mark['po_id'],
                            't_assign_courses_id' => $id,
                            'teacher_id' => $teacherAssigns->user_id,
                            'exam_id' => $mark['exam_id'],
                            'marks' => $mark['mark'],
                            'total'=>$mark['total']
                            // 'p_marks' => ($mark['mark'] / $mark['total']) * 100,
                        ]);
                    };
                }
            });
            ExamAssign::where('t_assign_courses_id', $id)->update(['mark_assign_done' => now()]);
            return redirect()->route('course.teacher.myCourse')->with('success', 'Mark Submitted Successfully');

        } catch (\Exception $e) {
            info($e->getMessage()??"some problem occured" ."in $e->getFile() line $e->getLine()");
            throw $e;
        }


    }
}
