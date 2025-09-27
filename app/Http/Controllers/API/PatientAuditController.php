<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientAudit;
use Illuminate\Support\Facades\Auth;

class PatientAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // GET /api/patients/{patient_id}/audits (Admin only)
    public function index($patient_id)
    {
        $user = Auth::user();
        if (! $user->hasRole('Admin')) abort(403);

        $patient = Patient::where('patient_id', $patient_id)->firstOrFail();
        $audits = PatientAudit::where('patient_id', $patient->id)->orderBy('created_at','desc')->get();

        return response()->json($audits);
    }
}
