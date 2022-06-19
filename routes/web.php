<?php

use App\Helper\Helper;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseOutcomeController;
use App\Http\Controllers\ExamAssignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramOutcomeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\CourseAssign;
use App\Models\Marks;
use App\Models\StudentBatch;
use App\Models\Students;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::post('/test', function () {
    $pdf = Pdf::loadView('pdf.index', ['data' => request()->html]);
    $copos = CourseAssign::where('course_id', 1)->get();
    $infos = [];
    foreach ($copos as $key => $value) {
        echo $value->id;
        $mrks = Marks::with('relCo', 'relPo')
            ->where('roll', 87)
            ->where('course_id', 1)
            ->where('batch_id', 1)
            ->where('co_id', $value->id)
            ->get();
        $info = [];
        $mk = 0;
        $tok = 0;
        foreach ($mrks as $k => $v) {
            $mk = $mk + (int)$v->marks;
            $tok = $tok + (int)$v->total;
            $info['co_name'] = $v->relCo->co_name;
            $info['po_name'] = $v->relPo->po_name;
            $info['mTotal'] = $mk;
            $info['tTotal'] = $tok;
        }
        $mk = 0;
        $tok = 0;
        $infos[$value->id] = $info;
        $info = [];
    }
    return $infos;
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
    return $pdf->download('prantho.pdf');
    return view('pdf.index');
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
    // Batch
    Route::resource('/batches', BatchController::class);
    //semester
    Route::resource('/semesters', SemesterController::class);
    //co & po
    Route::resource('/co', CourseOutcomeController::class);
    Route::resource('/po', ProgramOutcomeController::class);

    // Courses
    Route::get('/teacher/my-courses', [CourseController::class, 'myCourse'])->name('course.teacher.myCourse');
    Route::get('/teacher/co-po-exam/{id}', [ExamAssignController::class, 'makeExam'])->name('course.teacher.makeExam');
    Route::post('/teacher/co-po-exam/{id}', [ExamAssignController::class, 'makeExamStore'])->name('course.teacher.makeExamStore');
    Route::get('/exam-mark/{id}', [ExamAssignController::class, 'markCreate'])->name('exam.markCreate');
    Route::post('/exam-mark/{id}', [ExamAssignController::class, 'markStore'])->name('exam.markStore');



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
    Route::post('students', [UserController::class, 'studentStore'])->name('users.studentStore');
    Route::post('studentsBluk', [UserController::class, 'studentBluk'])->name('users.studentBluk');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
