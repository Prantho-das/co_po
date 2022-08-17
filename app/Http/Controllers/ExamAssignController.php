<?php

namespace App\Http\Controllers;

use App\Models\AssignMark;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\Department;
use App\Models\DraftMark;
use App\Models\ExamAssign;
use App\Models\Marks;
use App\Models\ProgramOutcome;
use App\Models\Semester;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\TeacherAssignCourse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ExamAssignController extends Controller
{

    public function deanYearPoReportIndex()
    {
        $pos = ProgramOutcome::all();
        return Inertia::render('Mark/MarkYearShow', [
            'pos' => $pos
        ]);
    }
    public function deanYearPoReport($year, $pid)
    {
        $satisfiedBatch = TeacherAssignCourse::whereYear('created_at', $year)->whereHas('relCoPo', function ($query) use ($pid) {
            return $query->where('po_id', $pid);
        })->with('relCoPo', 'relCourse')->get();

        $studentPo = [];
        $tempdata = [];
        $batchPo = [];
        $percentageCount =   [];
        foreach ($satisfiedBatch as $batch) {
            $students = Students::with('relBatch')->where('batch_id', $batch->batch_id)->get();

            foreach ($students as $student) {
                $result = AssignMark::with('relCo', 'relPo', 'relMarks.relExam')
                    ->where('course_id', $batch->course_id)
                    ->where('batch_id', $batch->batch_id)
                    ->where('student_id', $student->id)
                    ->where('po_id', $pid)
                    ->withSum('relMarks', 'marks')
                    ->withSum('relMarks', 'total')
                    ->get();

                $markSum = 0;
                $totalSum = 0;
                if ($result || count($result) > 0) {
                    foreach ($result as $po) {
                        $tempdata['co_no'] = [];
                        $tempdata['po_name'] = $po->relPo->po_name;
                        $tempdata['po_no'] = $po->relPo->po_no;
                        $markSum += $po->rel_marks_sum_marks;
                        $totalSum += $po->rel_marks_sum_total;
                        array_push($tempdata['co_no'], $po->relCo->co_no);
                        $marks = [];
                        foreach ($po->relMarks as $val) {
                            array_push($marks, ['mark' => $val->marks, 'co_no' => $po->relCo->co_no, 'total' => $val->total, 'name' => $val->relExam->name]);
                        }
                        $tempdata['marks'] = $marks;
                        $marks = [];
                        $tempdata['markSum'] = $markSum;
                        $tempdata['totalSum'] = $totalSum;
                        $tempdata['percent'] = ($markSum / $totalSum) * 100;
                    }
                    if ($tempdata && $tempdata['percent']) {

                        if ($tempdata['percent'] >= 80) {
                            $percentageCount['above_80'] = isset($percentageCount['above_80']) ? $percentageCount['above_80'] + 1 : 1;
                        } elseif ($tempdata['percent'] <= 79 && $tempdata['percent'] >= 60) {
                            $percentageCount['below_80'] = isset($percentageCount['below_80']) ? $percentageCount['below_80'] + 1 : 1;
                        } elseif ($tempdata['percent'] <= 59 && $tempdata['percent'] >= 40) {
                            $percentageCount['below_60'] = isset($percentageCount['below_60']) ? $percentageCount['below_60'] + 1 : 1;
                        } else {
                            $percentageCount['below_40'] = isset($percentageCount['below_40']) ? $percentageCount['below_40'] + 1 : 1;
                        }
                        array_push($studentPo, [
                            'name' => $student->name,
                            'roll' => $student->roll,
                            'po_result' => $tempdata
                        ]);
                    }
                    $tempdata = [];
                }
            }
            array_push($batchPo, [
                'course_name' => $batch->relCourse->c_name,
                'batch_id' => $batch->batch_id,
                'batch_name' => $batch->relBatch->name,
                'student_po' => $studentPo,
                'percentageCount' => $percentageCount,
                'id' => uniqid('diu')
            ]);
            $studentPo = [];
        }
        return $batchPo;
    }
    public function adminBatchPoorIndex()
    {
        $departments = Department::where('name', '!=', 'boat')->get();
        $semesters = Semester::all();
        return Inertia::render('Mark/MarkAdminBatchWeakShow', [
            'departments' => $departments,
            'semesters' => $semesters
        ]);
    }

    public function adminBatchPoorShow($bid, $cid)
    {

        $copoMarks = AssignMark::where('batch_id', $bid)
            ->where('course_id', $cid)
            ->with('relCourse', 'relPo', 'relCo')
            ->with('relStudent', 'relMarks.relExam')
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->get()
            ->groupBy('co_id');

        $weak = [];
        foreach ($copoMarks as $key => $value) {
            if (!empty($value) && count($value) > 0) {
                foreach ($value as $key => $val) {
                    $percentage = ((int)$val->rel_marks_sum_marks / (int)$val->rel_marks_sum_total) * 100;
                    $arr_index = Str::slug($val->relCo->co_name, '_');
                    if ($percentage < 40) {
                        $student = [];
                        $student['name'] = $val->relStudent->name;
                        $student['roll'] = $val->relStudent->roll;
                        $student['sum_mark'] = $val->rel_marks_sum_marks;
                        $student['total_mark'] = $val->rel_marks_sum_total;
                        $student['percentage'] = $percentage;
                        $student['rel_co'] = $val->relCo;
                        $student['rel_po'] = $val->relPo;
                        $student['marks'] = $val->relMarks;
                        // $weak[$arr_index][] = $student;
                        $weak[] = $student;
                        $student = [];
                    }
                }
            }
        }
        //  return $weak;
        return response(['weak' => $weak]);
    }
    public function adminBatchShow($bid, $cid)
    {
        $copoMarks = AssignMark::where('batch_id', $bid)
            ->where('course_id', $cid)
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->with('relCourse', 'relPo', 'relCo')
            ->with('relStudent')
            ->get()
            ->groupBy('co_id');

        $arr = [];
        $weak = [];
        foreach ($copoMarks as $key => $value) {
            if (!empty($value) && count($value) > 0) {
                foreach ($value as $key => $val) {
                    $percentage = ((int)$val->rel_marks_sum_marks / (int)$val->rel_marks_sum_total) * 100;
                    $arr_index = Str::slug($val->relCo->co_name, '_');
                    $arr[$arr_index]['co_name'] = $val->relCo->co_name;
                    $arr[$arr_index]['po_name'] = $val->relPo->po_name;
                    $arr[$arr_index]['co_id'] = $val->co_id;
                    $arr[$arr_index]['po_id']  = $val->po_id;
                    $arr[$arr_index]['co_no'] = $val->relCo->co_no;
                    $arr[$arr_index]['po_no']  = $val->relPo->po_no;
                    $arr[$arr_index]['result']  = $value;
                    if ($percentage >= 80) {
                        $arr[$arr_index]['80'] = isset($arr[$arr_index]['80']) ? $arr[$arr_index]['80'] + 1 : 1;
                    } elseif ($percentage <= 79 && $percentage >= 60) {
                        $arr[$arr_index]['79-60'] = isset($arr[$arr_index]['79-60']) ? $arr[$arr_index]['79-60'] + 1 : 1;
                    } elseif ($percentage <= 59 && $percentage >= 40) {
                        $arr[$arr_index]['59-40'] = isset($arr[$arr_index]['59-40']) ? $arr[$arr_index]['59-40'] + 1 : 1;
                    } else {
                        $weak[$arr_index][] = $val;
                        $arr[$arr_index]['below_40'] = isset($arr[$arr_index]['below_40']) ? $arr[$arr_index]['below_40'] + 1 : 1;
                    }
                }
            }
        }
        return response(['result' => $arr, 'weak' => $weak]);
    }


    public function adminBatchIndex()
    {
        $departments = Department::where('name', '!=', 'boat')->get();
        $semesters = Semester::all();
        return Inertia::render('Mark/MarkAdminBatchShow', [
            'departments' => $departments,
            'semesters' => $semesters
        ]);
    }
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
        // $tcourse->exam_assign_status = 1;
        // $tcourse->save();
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
    public function markEdit(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);

        $examAssigns = ExamAssign::where('t_assign_courses_id', $id)->whereNotNull('mark_assign_done')->get();

        if (count($examAssigns) == 0) {
            return back()->with('error', 'Assign Mark First');
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
                $mark = Marks::where('exam_id', $exam->id)->where('student_id', $student->id)->first();
                array_push($info["marks"], [
                    "exam_id" => $exam->id,
                    "exam_name" => $exam->name,
                    "copo_id" => $exam->copo_id,
                    "co_id" => $exam->co_id,
                    "po_id" => $exam->po_id,
                    "t_assign_courses_id" => $exam->t_assign_courses_id,
                    "teacher_id" => $exam->teacher_id,
                    "mark_id" => $mark->id ?? "",
                    "mark" => $mark->marks ?? 0,
                    "total" => $exam->marks,
                ]);
            }
            array_push($examInfo, $info);
            $info = [];
        }
        return Inertia::render('TeacherPages/MarksUpdate', [
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
                if ($mark['mark']) {
                    if ($assignMark) {
                        Marks::create([
                            'student_id' => $s_id,
                            'assign_marks_id' => $assignMark->id,
                            'exam_id' => $mark['exam_id'],
                            'marks' => $mark['mark'],
                            'total' => $mark['total']
                        ]);
                    } else {
                        $newAssignMark = AssignMark::create([
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
                        Marks::create([
                            'student_id' => $s_id,
                            'assign_marks_id' => $newAssignMark->id,
                            'exam_id' => $mark['exam_id'],
                            'marks' => $mark['mark'],
                            'total' => $mark['total']
                        ]);
                    }
                    ExamAssign::findOrFail($mark['exam_id'])->update(['mark_assign_done' => now()]);
                    DraftMark::where('t_assign_courses_id', $id)->delete();
                }
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
    public function markUpdate(Request $req, $id)
    {

        TeacherAssignCourse::findOrFail($id);

        foreach ($req->all() as $value) {
            foreach ($value['marks'] as $mark) {
                if ($mark['mark']) {
                    Marks::where('id', $mark['mark_id'])->update([
                        'marks' => $mark['mark'],
                    ]);
                }
            }
        }


        return redirect()->back()->with('success', 'Mark Updated Successfully');
    }

    public function markBatchDownload()
    {
        $content = '<style>' . file_get_contents(public_path() . '/css/app.css') . '</style>';
        $comment = "";
        if (!is_null(request()->comment) || isset(request()->comment)) {
            $comment = "<span margin-bottom:0px !important;><span style='font-weight:bold;'>Comment:</span>" . request()->comment . "</span>";
        }
        $pdf = Pdf::loadView('pdf.index', ['data' => trim(request()->html), 'content' => $content, 'teacherName' => request()->teacherName, 'batchName' => request()->batchName, 'courseName' => request()->courseName, 'courseCode' => request()->courseCode, 'comment' => $comment]);

        return $pdf->download('test.pdf');
    }
    public function markYearDownload()
    {
        $content = '<style>' . file_get_contents(public_path() . '/css/app.css') . '</style>';
        $pdf = Pdf::loadView('pdf.studentYearDeanMark',
        ['data' => trim(request()->html), 'content' => $content, 'po' => request()->po, 'year' => request()->year]);

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
                // ->where('batch_id', $teacherAssigns->batch_id)
                ->where('co_id', $value->co_id)
                ->withSum('relMarks', 'marks')
                ->withSum('relMarks', 'total')
                ->get();
            $comment = Comment::where('t_assign_course_id', $id)->where('co_id', $value->id)->first();

            $exams = ExamAssign::where('t_assign_courses_id', $id)->where('co_id', $value->co_id)->get();
            if (!empty($mrks) && count($mrks) > 0) {
                foreach ($mrks as $key => $val) {
                    $percentage = ((int)$val->rel_marks_sum_marks / (int)$val->rel_marks_sum_total) * 100;
                    $arr_index = Str::slug($val->relCo->co_name, '_');
                    $arr[$arr_index]['co_name'] = $val->relCo->co_name;
                    $arr[$arr_index]['po_name'] = $val->relPo->po_name;
                    $arr[$arr_index]['co_id'] = $value->co_id;
                    $arr[$arr_index]['po_id']  = $value->po_id;
                    $arr[$arr_index]['co_no'] = $value->relCo->co_no;
                    $arr[$arr_index]['po_no']  = $value->relPo->po_no;
                    $arr[$arr_index]['result']  = $mrks;
                    $arr[$arr_index]['exams'] = $exams;
                    $arr[$arr_index]['comment'] = $comment;
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
        return Inertia::render('Mark/MarkBatchShow', ['data' => $arr, 'teacherAssigns' => $teacherAssigns, 'chart_comment' => $comment]);
    }
    public function markStudentIndex()
    {
        $departments = Department::where('name', '!=', 'boat')->get();
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

        $result = AssignMark::with('relCo', 'relPo', 'relMarks.relExam')
            ->where('course_id', $cid)
            ->where('batch_id', $bid)
            ->where('student_id', $sid)
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->get();
        $pos = $result->groupBy('po_id');
        $po_result = [];
        $tempdata = [];
        foreach ($pos as $po) {
            $tempdata['co_no'] = [];
            $markSum = 0;
            $totalSum = 0;
            foreach ($po as $value) {
                $tempdata['po_name'] = $value->relPo->po_name;
                $tempdata['po_no'] = $value->relPo->po_no;
                $markSum += $value->rel_marks_sum_marks;
                $totalSum += $value->rel_marks_sum_total;
                array_push($tempdata['co_no'], $value->relCo->co_no);
                $marks = [];
                foreach ($value->relMarks as $val) {
                    array_push($marks, ['mark' => $val->marks, 'co_no' => $value->relCo->co_no, 'total' => $val->total, 'name' => $val->relExam->name]);
                }
                $tempdata['marks'] = $marks;
                $marks = [];
            }
            $tempdata['markSum'] = $markSum;
            $tempdata['totalSum'] = $totalSum;
            $tempdata['percent'] = ($markSum / $totalSum) * 100;
            array_push($po_result, $tempdata);
            $tempdata = [];
        }
        // return $po_result;
        return response(['result' => $result, 'po_result' => $po_result]);
    }

    public function draftMark(Request $req, $id)
    {
        $teacherAssigns = TeacherAssignCourse::findOrFail($id);

        foreach ($req->all() as $value) {
            $s_id = $value['student_id'];
            foreach ($value['marks'] as $mark) {
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




    //student
    public function markShowIndex()
    {
        $courses = Course::get();
        return Inertia::render('Student/Front/MarkStudentShow', ['courses' => $courses]);
    }
    public function markShowShow($cid)
    {
        $student = Auth::guard('student')->user();
        $result = AssignMark::with('relCo', 'relPo', 'relMarks.relExam')
            ->where('course_id', $cid)
            ->where('batch_id', $student->batch_id)
            ->where('student_id', $student->id)
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->get();
        $pos = $result->groupBy('po_id');
        $po_result = [];
        $tempdata = [];
        foreach ($pos as $po) {
            $tempdata['co_no'] = [];
            $markSum = 0;
            $totalSum = 0;
            foreach ($po as $value) {
                $tempdata['po_name'] = $value->relPo->po_name;
                $tempdata['po_no'] = $value->relPo->po_no;
                $markSum += $value->rel_marks_sum_marks;
                $totalSum += $value->rel_marks_sum_total;
                array_push($tempdata['co_no'], $value->relCo->co_no);
                $marks = [];
                foreach ($value->relMarks as $val) {
                    array_push($marks, ['mark' => $val->marks, 'co_no' => $value->relCo->co_no, 'total' => $val->total, 'name' => $val->relExam->name]);
                }
                $tempdata['marks'] = $marks;
                $marks = [];
            }
            $tempdata['markSum'] = $markSum;
            $tempdata['totalSum'] = $totalSum;
            $tempdata['percent'] = ($markSum / $totalSum) * 100;
            array_push($po_result, $tempdata);
            $tempdata = [];
        }
        return response(['result' => $result, 'po_result' => $po_result]);
    }
    public function completePoIndex()
    {
        $student = Auth::guard('student')->user();
        $result = AssignMark::with('relCo', 'relPo', 'relMarks.relExam')
            ->where('batch_id', $student->batch_id)
            ->where('student_id', $student->id)
            ->withSum('relMarks', 'marks')
            ->withSum('relMarks', 'total')
            ->get();
        $pos = $result->groupBy('po_id');
        $po_result = [];
        $tempdata = [];
        foreach ($pos as $po) {
            $tempdata['co_no'] = [];
            $markSum = 0;
            $totalSum = 0;
            foreach ($po as $value) {
                $tempdata['po_name'] = $value->relPo->po_name;
                $tempdata['po_no'] = $value->relPo->po_no;
                $markSum += $value->rel_marks_sum_marks;
                $totalSum += $value->rel_marks_sum_total;
                array_push($tempdata['co_no'], $value->relCo->co_no);
                $marks = [];
                foreach ($value->relMarks as $val) {
                    array_push($marks, ['mark' => $val->marks, 'co_no' => $value->relCo->co_no, 'total' => $val->total, 'name' => $val->relExam->name]);
                }
                $tempdata['marks'] = $marks;
                $marks = [];
            }
            $tempdata['markSum'] = $markSum;
            $tempdata['totalSum'] = $totalSum;
            $tempdata['percent'] = ($markSum / $totalSum) * 100;
            array_push($po_result, $tempdata);
            $tempdata = [];
        }
        return Inertia::render('Student/Front/MarkPoShow', ['po_result' => $po_result]);
    }

    public function markComment()
    {
        request()->validate([
            'teacher_course' => 'required|exists:teacher_assign_courses,id',
            'comment' => 'required',
            'co_id' => 'required',
            'po_id' => 'required',
        ]);

        $teacherAssign = TeacherAssignCourse::findOrFail(request('teacher_course'));

        Comment::updateOrCreate([
            't_assign_course_id' => $teacherAssign->id,
            'co_id' => request('co_id'),
        ], [
            't_assign_course_id' => $teacherAssign->id,
            'teacher_id' => auth()->id(),
            'comment' => trim(request('comment')),
            'co_id' => request('co_id'),
            'po_id' => request('po_id'),
        ]);
        return back()->with(['success' => 'Comment Added Successfully']);
    }
}
