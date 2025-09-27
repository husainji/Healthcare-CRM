<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/dashboard', function () {
    // return view('dashboard');
    $user = Auth::user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('CRM Agent')) {
        return redirect()->route('crm.dashboard');
    } elseif ($user->hasRole('Doctor')) {
        return redirect()->route('doctor.dashboard');
    } elseif ($user->hasRole('Patient')) {
        return redirect()->route('patient.dashboard');
    } elseif ($user->hasRole('Lab Manager')) {
        return redirect()->route('lab.dashboard');
    }else{
    // fallback if no role
        Auth::guard('web')->logout();
        abort(403, 'Unauthorized');
    }

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// All dashboard routes require authentication and specific role
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
            ->name('admin.dashboard');
    });

    Route::middleware(['role:CRM Agent'])->group(function () {
        Route::get('/crm/dashboard', [DashboardController::class, 'crm'])
            ->name('crm.dashboard');
    });

    Route::middleware(['role:Doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DashboardController::class, 'doctor'])
            ->name('doctor.dashboard');
    });

    Route::middleware(['role:Patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patient'])
            ->name('patient.dashboard');
    });

    Route::middleware(['role:Lab Manager'])->group(function () {
        Route::get('/lab/dashboard', [DashboardController::class, 'lab'])
            ->name('lab.dashboard');
    });

});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/roles', [UserRoleController::class, 'index'])->name('users.roles.index');
    Route::post('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');
});

require __DIR__.'/auth.php';
