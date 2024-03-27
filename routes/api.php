<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileTeacherController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AppointmentAvailableController;
use App\Http\Controllers\AppointmentTeacherStudentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProfileStudentController;
use App\Http\Controllers\IntrestController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QualificationCourseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestCompleteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceTeacherController;
use App\Http\Controllers\TeachingMethodController;
use App\Http\Controllers\TeachingMethodUserController;
use Spatie\Permission\Contracts\Permission;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::group(['prefix' => 'profile_teacher'], function () {
        Route::post('store', [ProfileTeacherController::class, 'store']);
        Route::post('update', [ProfileTeacherController::class, 'update']);
        Route::get('getById/{id}', [ProfileTeacherController::class, 'getById']);
        Route::get('getmyProfile', [ProfileTeacherController::class, 'show']);

    });

    Route::group(['prefix' => 'profile_student'], function () {
        Route::post('store', [ProfileStudentController::class, 'store']);
        Route::post('update', [ProfileStudentController::class, 'update']);
        Route::get('getById/{id}', [ProfileTeacherController::class, 'getById']);
        Route::get('getmyProfile', [ProfileTeacherController::class, 'show']);
    });

    Route::group(['prefix' => 'ads'], function () {
        Route::post('store', [AdsController::class, 'store']);
        Route::post('update/{id}', [AdsController::class, 'update']);
        Route::get('getAll', [AdsController::class, 'index']);
        Route::get('getById/{id}', [AdsController::class, 'getById']);
    });

    Route::group(['prefix' => 'employee'], function () {
        Route::post('store', [EmployeeController::class, 'createEmployee']);
        Route::post('update/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::get('getById/{id}', [EmployeeController::class, 'getById']);
        Route::delete('delete/{id}', [EmployeeController::class, 'delete']);
    });

    Route::group(['prefix' => 'evaluation'], function () {
        Route::post('store', [EvaluationController::class, 'store']);
        Route::delete('delete/{id}', [EvaluationController::class, 'destroy']);
    });

    Route::group(['prefix' => 'intrest'], function () {
        Route::post('store', [IntrestController::class, 'store']);
        Route::post('update/{id}', [IntrestController::class, 'update']);
        Route::delete('delete/{id}', [IntrestController::class, 'destroy']);
    });

    Route::group(['prefix' => 'note'], function () {
        Route::post('store', [NoteController::class, 'store']);
        Route::delete('delete/{id}', [NoteController::class, 'destroy']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::post('store', [PermissionController::class, 'create']);
        Route::post('update/{id}', [PermissionController::class, 'update']);
        Route::get('getall', [PermissionController::class, 'getAll']);
        Route::get('getById/{id}', [PermissionController::class, 'getById']);
        Route::delete('delete/{id}', [PermissionController::class, 'delete']);
    });


    Route::group(['prefix' => 'QualificationCourse'], function () {
        Route::post('store', [QualificationCourseController::class, 'store']);
        Route::post('update/{id}', [QualificationCourseController::class, 'update']);
        Route::delete('delete/{id}', [QualificationCourseController::class, 'destroy']);
        Route::get('getall', [QualificationCourseController::class, 'index']);
        Route::get('getById/{id}', [QualificationCourseController::class, 'show']);

    });

    Route::group(['prefix' => 'report'], function () {
        Route::post('store', [ReportController::class, 'store']);
    });


    Route::group(['prefix' => 'role'], function () {
        Route::post('store', [RoleController::class, 'create']);
        Route::post('update/{id}', [RoleController::class, 'update']);
        Route::delete('delete/{id}', [RoleController::class, 'delete']);
    });

    Route::group(['prefix' => 'ServiceTeacher'], function () {
        Route::post('store', [ServiceTeacherController::class, 'store']);
        Route::post('update/{id}', [ServiceTeacherController::class, 'update']);
        Route::delete('delete/{id}', [ServiceTeacherController::class, 'destroy']);
        Route::get('getAll/{id}', [ServiceTeacherController::class, 'index']);
        Route::get('getById/{id}', [ServiceTeacherController::class, 'show']);
    });


    Route::group(['prefix' => 'TeachingMethod'], function () {
        Route::get('getById/{id}', [TeachingMethodController::class, 'show']);
        Route::post('store', [TeachingMethodController::class, 'store']);
        Route::post('update/{id}', [TeachingMethodController::class, 'update']);
        Route::delete('delete/{id}', [TeachingMethodController::class, 'destroy']);
        Route::get('getAll/{id}', [TeachingMethodController::class, 'index']);
    });


    Route::group(['prefix' => 'TeachingMethodUser'], function () {
        Route::post('store', [TeachingMethodUserController::class, 'store']);
        Route::delete('delete/{id}', [TeachingMethodUserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'appointmentAvailable'], function () {
        Route::post('store', [AppointmentAvailableController::class, 'store']);
        Route::delete('delete/{id}', [AppointmentAvailableController::class, 'destroy']);
        Route::get('getAll/{id}', [AppointmentAvailableController::class, 'index']);
        Route::get('getById/{id}', [AppointmentAvailableController::class, 'show']);
    });


    Route::group(['prefix' => 'appointmentStudentTeacher'], function () {
        Route::post('store', [AppointmentTeacherStudentController::class, 'store']);
        Route::delete('delete/{id}', [AppointmentTeacherStudentController::class, 'destroy']);
        Route::get('getStudentAppointment', [AppointmentTeacherStudentController::class, 'getStudentAppointment']);
        Route::get('getById/{id}', [AppointmentTeacherStudentController::class, 'show']);
        Route::get('getTeacherAppointmentAvailable/{teacher_id}', [AppointmentTeacherStudentController::class, 'getTeacherAppointmentAvailable']);
    });
});
