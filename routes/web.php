<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseOutcomeController;
use App\Http\Controllers\ProgramOutcomeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SessionController;
use App\Models\CourseOutcome;
use App\Models\SessionYear;
use Illuminate\Foundation\Application;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
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
    Route::post('/courseAssign', [CourseController::class, 'assignCoPo'])->name('course.assignCoPo');
    Route::get('/teacher-assign-show', [CourseController::class, 'assignTeacherShow'])->name('course.assignTeacherShow');
    Route::get('/teacher-assign-create', [CourseController::class, 'assignTeacherCreate'])->name('course.assignTeacherCreate');
    Route::post('/teacherAssign', [CourseController::class, 'assignTeacherStore'])->name('course.assignTeacherStore');
    Route::resource('/course', CourseController::class);


    // Users
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::get('students', [\App\Http\Controllers\UserController::class, 'index'])->name('users.studentsShow');
    Route::post('studentsBluk', [\App\Http\Controllers\UserController::class, 'index'])->name('users.studentsBluk');
    Route::post('users/create', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
