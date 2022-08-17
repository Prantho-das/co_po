<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseOutcomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExamAssignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramOutcomeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Models\AssignMark;
use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\DraftMark;
use App\Models\ExamAssign;
use App\Models\Marks;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\TeacherAssignCourse;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Str;


use function PHPUnit\Framework\isNull;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {

    $pid = 12;
    $year = 2022;
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
            'percentageCount' => $percentageCount
        ]);
        $studentPo = [];
    }
    return $batchPo;





    return view('pdf.index', ['data' => '<h1>prantho</h1>', 'content' => '<h1>prantho</h1>', 'teacherName' => 'prantho', 'batchName' => 'prantho', 'courseName' => 'prantho', 'courseCode' => 'prantho', 'comment' => 'prantho']);

    $poors = AssignMark::where('batch_id', 1)
        ->where('course_id', 1)
        ->withSum('relMarks', 'marks')
        ->withSum('relMarks', 'total')
        ->with('relCourse', 'relPo')
        ->with('relStudent')
        ->get();

    // return $poors;


    $mrks = AssignMark::where('batch_id', 1)
        ->where('course_id', 1)
        ->withSum('relMarks', 'marks')
        ->withSum('relMarks', 'total')
        ->with('relCourse', 'relPo')
        ->with('relStudent')
        ->get()->groupBy('co_id');


    //return $mrks;


    $pos = AssignMark::where('student_id', '=', '2')
        ->where('course_id', 1)
        ->withSum('relMarks', 'marks')
        ->withSum('relMarks', 'total')
        ->with('relCourse', 'relPo', 'relCo', 'relMarks.relExam')
        ->get()
        ->groupBy('po_id');
    // return $pos;
    $data = [];
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
        array_push($data, $tempdata);
        $tempdata = [];
    }
    return $data;

    // $copos = CourseAssign::where('course_id', 1)->get();
    // $arr = [];
    // foreach ($copos as $key => $value) {
    //     //dump($value->co_id);
    //     $mrks = AssignMark::with('relCo:id,co_name', 'relPo:id,po_name', 'relMarks')
    //         ->where('course_id', 1)
    //         ->where('batch_id', 1)
    //         ->where('co_id', $value->co_id)
    //         ->withSum('relMarks', 'marks')
    //         ->withSum('relMarks', 'total')
    //         ->get();
    //     //   dump($mrks);
    //     foreach ($mrks as $key => $val) {
    //         $percentage = ((int)$val->rel_marks_sum_marks / (int)$val->rel_marks_sum_total) * 100;
    //         $arr_index = Str::slug($val->relCo->co_name, '_');
    //         $arr[$arr_index]['co_name'] = $val->relCo->co_name;
    //         $arr[$arr_index]['po_name'] = $val->relPo->po_name;
    //         $arr[$arr_index]['co_id'] = $value->co_id;
    //         $arr[$arr_index]['po_id']  = $value->po_id;
    //         if ($percentage >= 80) {
    //             $arr[$arr_index]['80'] = isset($arr[$arr_index]['80']) ? $arr[$arr_index]['80'] + 1 : 1;
    //         } elseif ($percentage <= 79 && $percentage >= 60) {
    //             $arr[$arr_index]['79-60'] = isset($arr[$arr_index]['79-60']) ? $arr[$arr_index]['79-60'] + 1 : 1;
    //         } elseif ($percentage <= 59 && $percentage >= 40) {
    //             $arr[$arr_index]['59-40'] = isset($arr[$arr_index]['59-40']) ? $arr[$arr_index]['59-40'] + 1 : 1;
    //         } else {
    //             $arr[$arr_index]['below_40'] = isset($arr[$arr_index]['below_40']) ? $arr[$arr_index]['below_40'] + 1 : 1;
    //         }
    //     }
    // }
    // $arr[$arr_index]['below_40'] = [
    //     'co_id' => $value->co_id,
    //     'po_id' => $value->po_id,
    //     'student_count' => isset($arr[$arr_index]['below_40']['student_count']) ? $arr[$arr_index]['below_40']['student_count'] + 1 : 1,
    // ];
    // echo '<pre>';
    // return response()->json($arr);
    // echo '</pre>';
    // return false;
    // $pdf = Pdf::loadView('pdf.index', ['data' => request()->html]);
    // $copos = CourseAssign::where('course_id', 1)->get();
    // $infos = [];
    // foreach ($copos as $key => $value) {
    //     echo $value->id;
    //     $mrks = Marks::with('relCo', 'relPo')
    //         ->where('roll', 87)
    //         ->where('course_id', 1)
    //         ->where('batch_id', 1)
    //         ->where('co_id', $value->id)
    //         ->get();
    //     $info = [];
    //     $mk = 0;
    //     $tok = 0;
    //     foreach ($mrks as $k => $v) {
    //         $mk = $mk + (int)$v->marks;
    //         $tok = $tok + (int)$v->total;
    //         $info['co_name'] = $v->relCo->co_name;
    //         $info['po_name'] = $v->relPo->po_name;
    //         $info['mTotal'] = $mk;
    //         $info['tTotal'] = $tok;
    //     }
    //     $mk = 0;
    //     $tok = 0;
    //     $infos[$value->id] = $info;
    //     $info = [];
    // }
    // return $infos;
    // return $cp;
    //return $copos;
    // $marks = Marks::with('relCo', 'relPo')->where('roll', 41)->where('course_id', 1)->where('batch_id', 1)->get()->groupBy('co_id');
    // echo "<pre>";
    // $m = [];
    // foreach ($marks as $key => $value) {
    //     $mk = 0;
    //     $tok = 0;
    //     foreach ($value as $k => $v) {
    //         $mk = $mk + (int)$v->marks;
    //         $tok = $tok + (int)$v->total;
    //         $m['co_name'] = $v->relCo->co_name;
    //         $m['po_name'] = $v->relPo->po_name;
    //         $m['mTotal'] = $mk;
    //         $m['tTotal'] = $tok;
    //     }
    //     $mk = 0;
    //     $tok = 0;
    // }
    // dump($m);
    // echo "</pre>";
    // $content = '<style>' . file_get_contents(public_path().'/css/app.css') . '</style>';
    // $pdf = Pdf::loadView('pdf.index', ['data' => request()->html, 'content' => $content, 'teacherName'=>request()->teacherName, 'batchName'=>request()->batchName, 'courseName'=>request()->courseName, 'courseCode'=> request()->courseCode]);

    // return $pdf->download('test.pdf');
    // return Inertia::render('PieChart', ['data'=>['prantho']]);


    //mark store
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
});
Route::get('/', function () {
    return Inertia::render('Welcome', ['canLogin' => Route::has('login')]);
});

Route::get('/dashboard', function () {
    $course = Course::count();
    $student = Students::count();
    $teacher = User::where('role', "TEACHER")->count();
    $batch = StudentBatch::count();
    $dashboard = [
        'course' => $course,
        'student' => $student,
        'teacher' => $teacher,
        'batch' => $batch,
    ];
    return Inertia::render('Dashboard', $dashboard);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    // Session
    Route::resource('/sessions', SessionController::class);
    // Department
    Route::resource('/department', DepartmentController::class);
    // Batch
    Route::resource('/batches', BatchController::class);
    //semester
    Route::resource('/semesters', SemesterController::class);
    //co & po
    Route::resource('/co', CourseOutcomeController::class);
    Route::resource('/po', ProgramOutcomeController::class);

    // Courses
    Route::get('/teacher/my-courses', [CourseController::class, 'myCourse'])->name('course.teacher.myCourse');
    Route::get('/teacher/my-completed-courses', [CourseController::class, 'myCompletedCourse'])->name('course.teacher.myCompletedCourse');
    Route::post('/teacher/my-completed-courses/{id}', [CourseController::class, 'completeCourse'])->name('course.teacher.completeCourse');


    Route::get('/teacher/co-po-exam/{id}', [ExamAssignController::class, 'makeExam'])->name('course.teacher.makeExam');
    Route::post('/teacher/co-po-exam/{id}', [ExamAssignController::class, 'makeExamStore'])->name('course.teacher.makeExamStore');
    Route::get('/exam-mark/{id}', [ExamAssignController::class, 'markCreate'])->name('exam.markCreate');
    Route::get('/exam-mark-edit/{id}', [ExamAssignController::class, 'markEdit'])->name('exam.markEdit');
    Route::post('/exam-mark-update/{id}', [ExamAssignController::class, 'markUpdate'])->name('exam.markUpdate');

    Route::post('/exam-mark/{id}', [ExamAssignController::class, 'markStore'])->name('exam.markStore');
    Route::post('/exam-mark-draft/{id}', [ExamAssignController::class, 'draftMark'])->name('exam.markDraft');
    Route::post('/teacher/exam-mark-comment', [ExamAssignController::class, 'markComment'])->name('exam.markComment');


    Route::get('/mark-show/batch/{id}', [ExamAssignController::class, 'markBatchShow'])->name('exam.markBatchShow');


    Route::get('/mark-show/student', [ExamAssignController::class, 'markStudentIndex'])->name('exam.markStudentIndex');
    Route::get('/mark-show/student/{did}/{bid}/{sid}/{cid}', [ExamAssignController::class, 'markStudentShow'])->name('exam.markStudentShow');
    Route::post('/mark-download/batch', [ExamAssignController::class, 'markBatchDownload'])->name('exam.markBatchDownload');

    Route::post('dean/mark-year-po-download', [ExamAssignController::class, 'markYearDownload'])->name('exam.markYearDownload');

    Route::get('admin/mark-show/batch', [ExamAssignController::class, 'adminBatchIndex'])->name('exam.adminBatchIndex');
    Route::get('admin/mark-show/batch/{bid}/{cid}', [ExamAssignController::class, 'adminBatchShow'])->name('exam.adminBatchShow');

    Route::get('admin/mark-show/poor-student', [ExamAssignController::class, 'adminBatchPoorIndex'])->name('exam.adminBatchPoorIndex');
    Route::get('admin/mark-show/poor-student/{bid}/{cid}', [ExamAssignController::class, 'adminBatchPoorShow'])->name('exam.adminBatchPoorShow');
    Route::get('admin/year-po-mark-show', [ExamAssignController::class, 'deanYearPoReportIndex'])->name('exam.deanYearPoReportIndex');
    Route::get('admin/year-po-mark-show/{year}/{pid}', [ExamAssignController::class, 'deanYearPoReport'])->name('exam.deanYearPoReport');

    //Route::post('admin/mark-download/batch', [ExamAssignController::class, 'markBatchDownload'])->name('exam.markBatchDownload');


    Route::get('/teacher-assign-show', [CourseController::class, 'assignTeacherShow'])->name('course.assignTeacherShow');
    Route::get('/teacher-assign-create', [CourseController::class, 'assignTeacherCreate'])->name('course.assignTeacherCreate');
    Route::post('/courseAssign', [CourseController::class, 'assignCoPo'])->name('course.assignCoPo');
    Route::post('/teacherAssign', [CourseController::class, 'assignTeacherStore'])->name('course.assignTeacherStore');


    Route::resource('/course', CourseController::class);


    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/create', [UserController::class, 'store'])->name('users.store');

    Route::get('students', [UserController::class, 'studentShow'])->name('users.studentShow');
    Route::get('students/create', [UserController::class, 'studentCreate'])->name('users.studentCreate');

    Route::get('students/batch-info/{departmentId}', [UserController::class, 'studentBatchInfo'])->name('users.studentBatchInfo');
    Route::get('students/batch-student-info/{batchId}', [UserController::class, 'studentInfoByBatch'])->name('users.studentInfoByBatch');
    Route::get('/courses-in-semester/{semester}', [CourseController::class, 'courseSemester'])->name('course.courseInSemester');


    Route::post('students', [UserController::class, 'studentStore'])->name('users.studentStore');
    Route::post('studentsBulk', [UserController::class, 'studentBulk'])->name('users.studentBulk');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::group(['middleware' => ['auth:student'], 'as' => 'student.', 'prefix' => 'student/'], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Student/Front/StudentDashboard');
    })->name('dashboard');

    Route::get('batch-info/{departmentId}', [UserController::class, 'studentBatchInfo'])->name('studentBatchInfo');
    Route::get('batch-student-info/{batchId}', [UserController::class, 'studentInfoByBatch'])->name('studentInfoByBatch');
    Route::get('courses-in-semester/{semester}', [CourseController::class, 'courseSemester'])->name('courseInSemester');

    Route::get('mark-show', [ExamAssignController::class, 'markShowIndex'])->name('markShowIndex');
    Route::get('mark-show/{cid}', [ExamAssignController::class, 'markShowShow'])->name('markShowShow');

    Route::get('completed-po', [ExamAssignController::class, 'completePoIndex'])->name('completePoIndex');
});
