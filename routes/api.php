<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\PatientAuditController;

Route::middleware('auth:sanctum')->group(function () {

    // Patients
    Route::post('/patients', [PatientController::class, 'store'])
        ->middleware('role:Admin|CRM Agent');

    Route::get('/patients', [PatientController::class, 'index'])
        ->middleware('role:Admin|CRM Agent');

    Route::get('/patients/{patient_id}', [PatientController::class, 'show'])
        ->middleware('role:Admin|CRM Agent|Doctor|Patient');

    Route::put('/patients/{patient_id}', [PatientController::class, 'update'])
        ->middleware('role:Admin|CRM Agent');

    Route::patch('/patients/{patient_id}', [PatientController::class, 'update'])
        ->middleware('role:Admin|CRM Agent');

    Route::delete('/patients/{patient_id}', [PatientController::class, 'destroy'])
        ->middleware('role:Admin');

    // Patient audits - Admin only
    Route::get('/patients/{patient_id}/audits', [PatientAuditController::class, 'index'])
        ->middleware('role:Admin');

    // Appointments
    Route::post('/appointments', [AppointmentController::class, 'store'])
        ->middleware('role:Admin|CRM Agent');

    Route::get('/appointments', [AppointmentController::class, 'index'])
        ->middleware('role:Admin|CRM Agent');

    Route::get('/appointments/patient/{patient_id}', [AppointmentController::class, 'forPatient'])
        ->middleware('role:Admin|CRM Agent|Doctor|Patient');

    Route::get('/appointments/doctor/{doctor_id}', [AppointmentController::class, 'forDoctor'])
        ->middleware('role:Admin|CRM Agent|Doctor');

    Route::put('/appointments/{appointment_id}', [AppointmentController::class, 'update'])
        ->middleware('role:Admin|CRM Agent|Doctor');

    Route::patch('/appointments/{appointment_id}', [AppointmentController::class, 'update'])
        ->middleware('role:Admin|CRM Agent|Doctor');

    Route::delete('/appointments/{appointment_id}', [AppointmentController::class, 'destroy'])
        ->middleware('role:Admin|CRM Agent');
});
