<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Import Semua Controller di Satu Tempat
|--------------------------------------------------------------------------
*/

// Controller untuk User
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\DinasLuarController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;

// Controller untuk Admin (dengan alias agar tidak bentrok)
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\RecapController as AdminRecapController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\Admin\ToolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['register' => false]);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

Route::middleware(['auth'])->group(function () {

    // --- Rute Umum Setelah Login ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- Rute Khusus User ---
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::get('/izin/create', [IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::delete('/izin/{izin}', [IzinController::class, 'destroy'])->name('izin.destroy');

    Route::get('/dinas-luar/create', [DinasLuarController::class, 'create'])->name('dinasluar.create');
    Route::post('/dinas-luar', [DinasLuarController::class, 'store'])->name('dinasluar.store');
    Route::delete('/dinas-luar/{dinasLuar}', [DinasLuarController::class, 'destroy'])->name('dinasluar.destroy');

    Route::resource('reports', ReportController::class);
    Route::get('/reports/print/{month}/{year}', [ReportController::class, 'print'])->name('reports.print');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/documents', [ProfileController::class, 'uploadDocument'])->name('profile.documents.upload');
    Route::get('/profile/documents/{document}', [ProfileController::class, 'downloadDocument'])->name('profile.documents.download');
    Route::delete('/profile/documents/{document}', [ProfileController::class, 'destroyDocument'])->name('profile.documents.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat.index');

    Route::get('/pengajuan-cuti-sakit', [LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/pengajuan-cuti-sakit', [LeaveRequestController::class, 'store'])->name('leave.store');



    /*
    |--------------------------------------------------------------------------
    | RUTE KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        Route::redirect('/', '/admin/dashboard')->name('index');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Profil Admin
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
        
        Route::resource('siswa', PegawaiController::class);

        Route::get('/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
        Route::post('/documents/{document}/verify', [AdminDocumentController::class, 'verify'])->name('documents.verify');

        Route::post('/attendance/force', [AdminAttendanceController::class, 'forceStore'])->name('attendance.force');

        Route::get('/recap', [AdminRecapController::class, 'index'])->name('recap.index');
        Route::get('/recap/export', [AdminRecapController::class, 'exportExcel'])->name('recap.export');
        
        Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);

        // --- APPROVALS ---
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/izin/{izin}', [ApprovalController::class, 'processIzin'])->name('approvals.izin.process');
        Route::post('/approvals/dinas-luar/{dinasLuar}', [ApprovalController::class, 'processDinasLuar'])->name('approvals.dinasluar.process');
        Route::post('/approvals/leave/{leaveRequest}', [ApprovalController::class, 'processLeaveRequest'])->name('approvals.leave.process');

        // ✅ ROUTE YANG HILANG — INILAH YANG MEMPERBAIKI ERROR KAMU
        Route::get('/approvals/leave/attachment/{leaveRequest}', 
            [ApprovalController::class, 'attachment'])
            ->name('approvals.leave.attachment');

        // Tools Admin
        Route::get('/tools/backfill', [ToolController::class, 'showBackfillForm'])->name('tools.backfill.form');
        Route::post('/tools/backfill', [ToolController::class, 'processBackfill'])->name('tools.backfill.process');
    });
});
