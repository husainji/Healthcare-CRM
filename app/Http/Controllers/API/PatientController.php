<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    public function __construct()
    {
        // Sanctum auth is required for API access
        $this->middleware('auth:sanctum')->except([]);
    }

    // POST /api/patients (Admin, CRM Agent)
    public function store(StorePatientRequest $request)
    {
        $this->authorizeAction(['Admin','CRM Agent']);

        $data = $request->validated();
        $patient = Patient::create($data);

        return response()->json($patient, Response::HTTP_CREATED);
    }

    // GET /api/patients (Admin, CRM Agent)
    public function index(Request $request)
    {
        $this->authorizeAction(['Admin','CRM Agent']);

        $query = Patient::query();

        // search by first_name, last_name or phone_number
        if ($q = $request->query('q')) {
            $query->where(function($q2) use ($q) {
                $q2->where('first_name','like','%'.$q.'%')
                   ->orWhere('last_name','like','%'.$q.'%')
                   ->orWhere('phone_number','like','%'.$q.'%');
            });
        }

        $perPage = $request->query('per_page', 15);
        $patients = $query->paginate($perPage);

        return response()->json($patients);
    }

    // GET /api/patients/{patient_id} (Admin, CRM Agent, Doctor, Patient (their own))
    public function show($patient_id)
    {
        $patient = Patient::where('patient_id', $patient_id)->firstOrFail();

        $user = Auth::user();
        if ($user->hasAnyRole(['Admin','CRM Agent'])) {
            return response()->json($patient);
        }

        if ($user->hasRole('Doctor')) {

            return response()->json($patient);
        }

        if ($user->hasRole('Patient')) {
            
            if ($user->id === $patient->user_id) {
                return response()->json($patient);
            }
            abort(403, 'Access denied');
        }

        abort(403);
    }

    // PUT/PATCH /api/patients/{patient_id} (Admin, CRM Agent)
    public function update(UpdatePatientRequest $request, $patient_id)
    {
        $this->authorizeAction(['Admin','CRM Agent']);

        $patient = Patient::where('patient_id', $patient_id)->firstOrFail();
        $data = $request->validated();

        if (isset($data['insurance_details']) && is_array($data['insurance_details'])) {
            // will be cast by model
        }

        $patient->fill($data);
        $patient->save();

        return response()->json($patient);
    }

    // DELETE /api/patients/{patient_id} (Admin only)
    public function destroy($patient_id)
    {
        $this->authorizeAction(['Admin']);

        $patient = Patient::where('patient_id', $patient_id)->firstOrFail();
        $patient->delete();

        return response()->json(['message' => 'Deleted'], Response::HTTP_NO_CONTENT);
    }

    protected function authorizeAction(array $roles)
    {
        $user = Auth::user();
        if (! $user || ! $user->hasAnyRole($roles)) {
            abort(403);
        }
    }

    // GET /api/patients/{patient_id}/audits (Admin only) - moved to separate controller method per Task 4
}
