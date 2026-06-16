<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExperimentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('students', StudentController::class);
    
    Route::resource('attendance', AttendanceController::class)->except(['show']);
    Route::get('/attendance/face-recognition', [AttendanceController::class, 'faceRecognition'])->name('attendance.face');
    Route::post('/attendance/mark-via-face', [AttendanceController::class, 'markViaFace'])->name('attendance.mark.face');
    
    Route::prefix('experiment')->name('experiment.')->middleware(['is_admin'])->group(function () {
        Route::get('/capture', [ExperimentController::class, 'capture'])->name('capture');
        Route::post('/capture', [ExperimentController::class, 'doCapture'])->name('capture.do');
        Route::get('/train', [ExperimentController::class, 'train'])->name('train');
        Route::post('/train', [ExperimentController::class, 'doTrain'])->name('train.do');
        Route::get('/test', [ExperimentController::class, 'test'])->name('test');
        Route::post('/test', [ExperimentController::class, 'doTest'])->name('test.do');
        Route::get('/logs', [ExperimentController::class, 'logs'])->name('logs');
        Route::get('/metrics', [ExperimentController::class, 'metrics'])->name('metrics');
        Route::get('/threshold', [ExperimentController::class, 'threshold'])->name('threshold');
        Route::post('/metrics/calculate', [ExperimentController::class, 'calculateMetrics'])->name('metrics.calculate');
        Route::get('/metrics/calculate-by-condition', [ExperimentController::class, 'calculateMetricsByCondition'])->name('metrics.calculate-by-condition');
        Route::get('/metrics/calculate-threshold', [ExperimentController::class, 'calculateThresholdEvaluation'])->name('metrics.calculate-threshold');
    });
});

require __DIR__.'/auth.php';
