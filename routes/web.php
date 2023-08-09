<?php

use App\Http\Livewire\Admin\Student;
use App\Http\Livewire\Admin\Teacher;
use App\Http\Livewire\Admin\User;
use App\Http\Livewire\Adviser\Dashboard;
use App\Http\Livewire\Adviser\ReportHistory;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Adviser\Report;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\StudentsProfile;
use App\Http\Livewire\Adviser\StudentProfile;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\OffensesController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Adviser\ReportHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('test');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role'])->group(function () {

    // Admin Access
    Route::middleware(['can:admin-access'])->group(function () {

        Route::prefix('admin')->group(function () {
            Route::get('dashboard', \App\Http\Livewire\Admin\Dashboard::class);
            //Reports
            Route::get('reports', [ReportController::class, 'index']);
            Route::get('reports/add', [ReportController::class, 'create']);
            Route::get('reports/{anecdotal}/view', [ReportController::class, 'view'])->name('anecdotal.edit');

            //User Manage
            Route::get('update-acc', User::class);
            Route::get('add-acc', User::class);

            // Students Profile Area
            Route::get('student-profile', [StudentProfileController::class, 'index']);
            Route::get('student-profile/{profile}/view', [StudentProfileController::class, 'show'])->name('admin.profile.edit');
            Route::get('student-profile/add', StudentsProfile::class);
        });

        Route::prefix('admin/settings')->group(function () {
            // Settings Area - Offenses
            Route::get('offenses', [OffensesController::class, 'index']);
            Route::get('offenses/create', [OffensesController::class, 'create']);
            Route::post('offenses/store', [OffensesController::class, 'store']);
            Route::get('offenses/{offense}/edit', [OffensesController::class, 'edit'])->name('offenses.edit');
            Route::put('offenses/{id}', [OffensesController::class, 'update'])->name('admin.settings.offenses.update');
            // Employees
            Route::get('teachers', Teacher::class);

            // Classrooms
            Route::get('classrooms', [ClassroomController::class, 'index']);
            Route::get('classroom/create', [ClassroomController::class, 'create']);
            Route::post('classroom/store', [ClassroomController::class, 'store'])->name('admin.classroom.store');
            Route::put('classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
            //Edit Classroom
            Route::get('classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('classroom.edit');
            //View Classrooms
            Route::get('classroom/{classroom}/view', [ClassroomController::class, 'show'])->name('classroom.view');


            Route::get('audit-trail', function () {
                return view('admin.settings.audit-trail.index');
            });

            // Students Area
            Route::get('students', Student::class);
        });
    });

    // Adviser | Staff Area
    Route::middleware(['can:adviser-access'])->group(function () {
        Route::prefix('adviser')->group(function () {
            //*Dashboard
            Route::get('dashboard', Dashboard::class);
            //*Livewire Report Student
            Route::get('report/student', Report::class);
            //*Student Profile//livewire
            Route::get('students-profile', StudentProfile::class);
            Route::get('student-profile/{profile}/view', [StudentProfile::class, 'show'])->name('profile.view');
            //*History
            Route::get('report/history', ReportHistory::class);
            Route::get('report/history/{report}/view', [ReportHistory::class, 'view'])->name('report.view');
            Route::get('report/history/{report}/edit', [ReportHistory::class, 'edit'])->name('report.edit');
            //*Account Management
            Route::get('update-acc', User::class);
            Route::get('add-acc', User::class);
        });
    });
});

//traits
Route::resource('students', ReportHistory::class);
