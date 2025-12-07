<?php

use App\Http\Controllers\Print\InvoicePrintController;
use App\Http\Controllers\TenantSelectorController;
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

// Welcome/Home Route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Tenant selector routes (Development only - for local tenant switching)
if (app()->environment('local')) {
    Route::get('/select-tenant', [TenantSelectorController::class, 'index'])->name('select-tenant');
    Route::post('/select-tenant', [TenantSelectorController::class, 'select'])->name('select-tenant.select');
    Route::post('/select-tenant/clear', [TenantSelectorController::class, 'clear'])->name('select-tenant.clear');
}

// Print Routes
Route::prefix('print')->name('print.')->group(function () {
    // Invoice Print Routes
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('{record}/inline', [InvoicePrintController::class, 'inline'])->name('inline');
        Route::get('{record}/download', [InvoicePrintController::class, 'download'])->name('download');
    });
    
    // Medical Document Print Routes (if needed in the future)
    // Route::prefix('medical-documents')->name('medical-documents.')->group(function () {
    //     Route::get('{record}/inline', [MedicalDocumentPrintController::class, 'inline'])->name('inline');
    //     Route::get('{record}/download', [MedicalDocumentPrintController::class, 'download'])->name('download');
    // });
});

// Note: Filament panels handle their own authentication and email verification routes
// If you need custom email verification routes, uncomment and customize below:
// Route::middleware(['auth:web', 'throttle:6,1'])->group(function () {
//     Route::get('/email/verify', function () {
//         return redirect()->route('filament.portal.pages.dashboard');
//     })->name('verification.notice');
//
//     Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
//         $request->fulfill();
//         return redirect()->route('filament.portal.pages.dashboard')
//             ->with('success', 'Email verified successfully!');
//     })->middleware(['signed'])->name('verification.verify');
//
//     Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
//         $request->user()->sendEmailVerificationNotification();
//         return back()->with('success', 'Verification link sent!');
//     })->middleware(['throttle:6,1'])->name('verification.send');
// });
