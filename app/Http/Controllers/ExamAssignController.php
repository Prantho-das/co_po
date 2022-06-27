<?php

namespace App\Http\Controllers;

use App\Models\AssignMark;
use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\Department;
use App\Models\DraftMark;
use App\Models\ExamAssign;
use App\Models\Marks;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\TeacherAssignCourse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

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
        if ($tcourse->course_complete_at == 1) {
            return back()->with('error', 'Exam Assign Done');
        }
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
        $tcourse->exam_assign_status = 1;
        $tcourse->save();
        return back()->with('success', 'Exam Created Successfully');
    }

    public function markCreate(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);
        $draft = DraftMark::where('t_assign_courses_id', $id)->get();

        $examAssigns = ExamAssign::where('t_assign_courses_id', $id)->whereNull('mark_assign_done')->get();

        if (count($examAssigns) == 0) {
            return back()->with('error', 'Mark Assign Done');
        }
        if (count($examAssigns) == 0) {
            return back()->with('error', 'No Exam Assigned');
        }
        $students = Students::where('batch_id', $teacherAssigns->batch_id)->get();
        // mark
        $info = [];
        $examInfo = [];
        foreach ($students as $key => $student) {
            $info["student_name"] = $student->name;
            $info["student_id"] = $student->id;
            $info["student_roll"] = $student->roll;
            $info["marks"] = [];
            foreach ($examAssigns as $key => $exam) {
                array_push($info["marks"], [
                    "exam_id" => $exam->id,
                    "exam_name" => $exam->name,
                    "copo_id" => $exam->copo_id,
                    "co_id" => $exam->co_id,
                    "po_id" => $exam->po_id,
                    "t_assign_courses_id" => $exam->t_assign_courses_id,
                    "teacher_id" => $exam->teacher_id,
                    "mark" => $this->getDraftMark($draft, $student->id, $exam->id) ?? "",
                    "total" => $exam->marks,
                ]);
            }
            array_push($examInfo, $info);
            $info = [];
        }
        return Inertia::render('TeacherPages/Marks', [
            'examAssigns' => $examAssigns,
            'students' => $students,
            'teacherAssigns' => $teacherAssigns,
            'examInfo' => $examInfo
        ]);
    }
    private function getDraftMark($draft, $sId, $eId)
    {

        foreach ($draft as $key => $value) {
            if ($value->student_id == $sId && $value->exam_id == $eId) {
                return $value->mark;
            }
        }
        return null;
    }
    public function markStore(Request $req, $id)
    {
        // $req->validate([
        //     '*.marks' => 'required',
        //     '*.marks.*.exam_id' => 'required',
        //     '*.marks.*.exam_name' => 'required',
        //     '*.marks.*.copo_id' => 'required',
        //     '*.marks.*.co_id' => 'required',
        //     '*.marks.*.po_id' => 'required',
        //     '*.marks.*.t_assign_courses_id' => 'required',
        //     '*.marks.*.teacher_id' => 'required',
        //     '*.marks.*.mark' => 'required',
        //     '*.marks.*.total' => 'required',
        // ],[
        //     '*.marks.*.mark.required' => 'Marks is required',
        // ]);
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);

        foreach ($req->all() as $value) {
            $s_id = $value['student_id'];
            $roll = $value['student_roll'];
            foreach ($value['marks'] as $mark) {
                $examInfo = ExamAssign::findOrFail($mark['exam_id']);
                if ($examInfo->marks < 0 || (int)$mark['mark'] > $examInfo->marks) {
                    return back()->with('error', 'Marks must be between 0 and total marks');
                }

                $assignMark = DB::table('assign_marks')
                    ->where('student_id', $s_id)
                    ->where('copo_id', $mark['copo_id'])
                    ->where('co_id', $mark['co_id'])
                    ->where('course_id', $teacherAssigns->course_id)
                    ->first();
                // if ($mark['mark']) {
                if ($assignMark) {
                    DB::table('marks')->insert([
                        'student_id' => $s_id,
                        'assign_marks_id' => $assignMark->id,
                        'exam_id' => $mark['exam_id'],
                        'marks' => $mark['mark'],
                        'total' => $mark['total']
                    ]);
                } else {
                    $newAssignMark = DB::table('assign_marks')->insertGetId([
                        'student_id' => $s_id,
                        'batch_id' => $teacherAssigns->batch_id,
                        'roll' => $roll,
                        'course_id' => $teacherAssigns->course_id,
                        'copo_id' => $mark['copo_id'],
                        'co_id' => $mark['co_id'],
                        'po_id' => $mark['po_id'],
                        't_assign_courses_id' => $id,
                        'teacher_id' => $teacherAssigns->user_id,
                    ]);
                    DB::table('marks')->insert([
                        'student_id' => $s_id,
                        'assign_marks_id' => $newAssignMark,
                        'exam_id' => $mark['exam_id'],
                        'marks' => $mark['mark'],
                        'total' => $mark['total']
                    ]);
                }
                ExamAssign::findOrFail($mark['exam_id'])->update(['mark_assign_done' => now()]);
                DraftMark::where('t_assign_courses_id',$id)->delete();
                // }
            }


            // $req->validate([
            // ]);
            // try {
            //     DB::transaction(function () use ($req, $teacherAssigns,$id) {
            //         foreach ($req->all() as $value) {
            //             $s_id = $value['student_id'];
            //             $roll = $value['student_roll'];
            //             foreach ($value['marks'] as $mark) {
            //                 $examInfo=ExamAssign::findOrFail($mark['exam_id']);
            //                 if($examInfo->marks < 0 || (int)$mark['mark']> $examInfo->marks ){
            //                     return back()->with('error', 'Marks must be between 0 and total marks');
            //                 }
            //                 Marks::create([
            //                     'student_id' => $s_id,
            //                     'batch_id' => $teacherAssigns->batch_id,
            //                     'roll' => $roll,
            //                     'course_id' => $teacherAssigns->course_id,
            //                     'copo_id' => $mark['copo_id'],
            //                     'co_id' => $mark['co_id'],
            //                     'po_id' => $mark['po_id'],
            //                     't_assign_courses_id' => $id,
            //                     'teacher_id' => $teacherAssigns->user_id,
            //                     'exam_id' => $mark['exam_id'],
            //                     'marks' => $mark['mark'],
            //                     'total'=>$mark['total']
            //                     // 'p_marks' => ($mark['mark'] / $mark['total']) * 100,
            //                 ]);
            //             };
            //         }
            //     });
            //     ExamAssign::where('t_assign_courses_id', $id)->update(['mark_assign_done' => now()]);
            //     return redirect()->route('course.teacher.myCourse')->with('success', 'Mark Submitted Successfully');

            // } catch (\Exception $e) {
            //     info($e->getMessage()??"some problem occured" ."in $e->getFile() line $e->getLine()");
            //     throw $e;
            // }


        }
        return redirect()->route('course.teacher.myCourse')->with('success', 'Mark Submitted Successfully');
    }

    public function markBatchDownload()
    {
        $content = '<style>' . file_get_contents(public_path() . '/css/app.css') . '</style>';
        $pdf = Pdf::loadView('pdf.index', ['data' => request()->html, 'content' => $content, 'teacherName' => request()->teacherName, 'batchName' => request()->batchName, 'courseName' => request()->courseName, 'courseCode' => request()->courseCode, 'comment' => request()->comment]);

        return $pdf->download('test.pdf');
    }
    public function markBatchShow($id)
    {
        $teacherAssigns = TeacherAssignCourse::with('relTeacher', 'relBatch', 'relCourse')->findOrFail($id);

        $copos = CourseAssign::where('course_id', $teacherAssigns->course_id)->get();
        $arr = [];
        foreach ($copos as $key => $value) {
            $mrks = AssignMark::with('relCo:id,co_name', 'relPo:id,po_name', 'relMarks')
                ->where('course_id', $teacherAssigns->course_id)
                ->where('batch_id', $teacherAssigns->batch_id)
                ->where('batch_id', $teacherAssigns->batch_id)
                ->where('co_id', $value->co_id)
                ->withSum('relMarks', 'marks')
                ->withSum('relMarks', 'total')
                ->get();
            if (!empty($mrks) && count($mrks) > 0) {
                foreach ($mrks as $key => $val) {
                    $percentage = ((int)$val->rel_marks_sum_marks / (int)$val->rel_marks_sum_total) * 100;
                    $arr_index = Str::slug($val->relCo->co_name, '_');
                    $arr[$arr_index]['co_name'] = $val->relCo->co_name;
                    $arr[$arr_index]['po_name'] = $val->relPo->po_name;
                    $arr[$arr_index]['co_id'] = $value->co_id;
                    $arr[$arr_index]['po_id']  = $value->po_id;
                    $arr[$arr_index]['result']  = $mrks;
                    if ($percentage >= 80) {
                        $arr[$arr_index]['80'] = isset($arr[$arr_index]['80']) ? $arr[$arr_index]['80'] + 1 : 1;
                    } elseif ($percentage <= 79 && $percentage >= 60) {
                        $arr[$arr_index]['79-60'] = isset($arr[$arr_index]['79-60']) ? $arr[$arr_index]['79-60'] + 1 : 1;
                    } elseif ($percentage <= 59 && $percentage >= 40) {
                        $arr[$arr_index]['59-40'] = isset($arr[$arr_index]['59-40']) ? $arr[$arr_index]['59-40'] + 1 : 1;
                    } else {
                        $arr[$arr_index]['below_40'] = isset($arr[$arr_index]['below_40']) ? $arr[$arr_index]['below_40'] + 1 : 1;
                    }
                }
            }
        }
        // return $arr;
        return Inertia::render('Mark/MarkBatchShow', ['data' => $arr, 'teacherAssigns' => $teacherAssigns]);
    }
    public function markStudentIndex()
    {
        $departments = Department::get();
        $batches = StudentBatch::where('status', 1)->get();
        $courses = Course::get();
        return Inertia::render('Mark/MarkStudentShow', ['batches' => $batches, 'departments' => $departments, 'courses' => $courses]);
    }
    public function markStudentShow($did, $bid, $sid, $cid)
    {

        // $dpt = Department::findOrFail($did);
        // $sbatch = StudentBatch::findOrFail($bid);
        // $crs = Course::findOrFail($cid);
        // $stu = Students::findOrFail($sid);

        return $marks = AssignMark::with('relCo', 'relPo', 'relMarks.relExam')
            ->where('course_id', $cid)
            ->where('batch_id', $bid)
            ->where('student_id', $sid)
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->get();
    }

    public function draftMark(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);

        foreach ($req->all() as $value) {
            $s_id = $value['student_id'];
            foreach ($value['marks'] as $mark) {
                // DraftMark::createOrUpdate(
                //     [],
                //     []
                // );
                $draftMark = DraftMark::where("student_id", $s_id)
                    ->where("t_assign_courses_id", $id)
                    ->where("exam_id", $mark['exam_id'])->first();
                if ($draftMark) {
                    $draftMark->mark = $mark['mark'];
                    $draftMark->save();
                } else {
                    DraftMark::create([
                        "student_id" => $s_id,
                        "t_assign_courses_id" => $id,
                        "exam_id" => $mark['exam_id'],
                        "mark" => $mark['mark'],
                    ]);
                }
            }
        }
        return back()->with('success', 'Marks Drafted Successfully');
    }
}
