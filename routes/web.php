<?php

use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MpesaParserController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member routes
    Route::get('/member/contributions', [ContributionController::class, 'index'])->name('member.contributions');
    Route::post('/member/contributions', [ContributionController::class, 'store'])->name('member.contributions.store');
    Route::post('/member/contributions/parse-sms', [ContributionController::class, 'parseSms'])->name('member.contributions.parseSms');

    Route::get('/member/loans', [LoanController::class, 'index'])->name('member.loans');
    Route::post('/member/loans', [LoanController::class, 'store'])->name('member.loans.store');
    Route::post('/member/loans/{loan}/repay', [LoanController::class, 'repay'])->name('member.loans.repay');

    // Treasurer routes (consider adding role middleware for extra security)
    Route::get('/treasurer/loans/pending', [LoanController::class, 'pending'])->name('treasurer.loans.pending');

    // ✅ ADD THESE TWO LINES – missing approval/rejection routes
    Route::post('/treasurer/loans/{loan}/approve', [LoanController::class, 'approve'])->name('treasurer.loans.approve');
    Route::post('/treasurer/loans/{loan}/reject', [LoanController::class, 'reject'])->name('treasurer.loans.reject');

    Route::get('/treasurer/penalties', [PenaltyController::class, 'index'])->name('treasurer.penalties');
    Route::post('/treasurer/penalties/{fine}/mark-paid', [PenaltyController::class, 'markPaid'])->name('treasurer.penalties.markPaid');

    Route::get('/treasurer/sms-parser', [MpesaParserController::class, 'index'])->name('treasurer.sms-parser');
    Route::post('/treasurer/sms-parser', [MpesaParserController::class, 'store'])->name('treasurer.sms-parser.store');

    // Report routes (member and treasurer)
    Route::get('/reports/member/{user}', [ReportController::class, 'memberStatement'])->name('reports.member');
    Route::get('/reports/treasurer', [ReportController::class, 'treasurerReports'])->name('reports.treasurer');
});

require __DIR__.'/auth.php';