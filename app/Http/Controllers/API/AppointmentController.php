<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // POST /api/appointments (Admin, CRM Agent)
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user->hasAnyRole(['Admin','CRM Agent'])) abort(403);

        $data = $request->validate([
            'patient_id' => 'required|string|exists:patients,patient_id',
            'doctor_id'  => 'required|integer|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::where('patient_id', $data['patient_id'])->firstOrFail();
        $doctor = Doctor::findOrFail($data['doctor_id']);

        // check availability - no other appointment for same doctor at same date/time and not cancelled
        $conflict = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->whereIn('status', ['Scheduled','Confirmed'])
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'Doctor is not available at the selected date and time'], Response::HTTP_CONFLICT);
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json($appointment, Response::HTTP_CREATED);
    }

    // GET /api/appointments (Admin, CRM Agent)
    public function index()
    {
        $user = Auth::user();
        if (! $user->hasAnyRole(['Admin','CRM Agent'])) abort(403);

        $appointments = Appointment::with(['patient','doctor'])->paginate(20);
        return response()->json($appointments);
    }

    // GET /api/appointments/patient/{patient_id}
    public function forPatient($patient_id)
    {
        $patient = Patient::where('patient_id', $patient_id)->firstOrFail();
        $user = Auth::user();

        if ($user->hasAnyRole(['Admin','CRM Agent'])) {
            // ok
        } elseif ($user->hasRole('Patient')) {
            // ensure owner
            if ($user->email !== $patient->email) abort(403);
        } elseif ($user->hasRole('Doctor')) {
            // ensure doctor sees only appointments where they are assigned
            // But this endpoint is for a specific patient, we should allow doctor only if they have appointments with this patient
            $isRelated = Appointment::where('patient_id',$patient->id)
                ->where('doctor_id', function($q) use ($user){
                    $q->select('id')->from('doctors')->where('user_id', $user->id);
                })->exists();

            if (! $isRelated) abort(403);
        } else {
            abort(403);
        }

        $appointments = Appointment::where('patient_id', $patient->id)->with('doctor')->get();
        return response()->json($appointments);
    }

    // GET /api/appointments/doctor/{doctor_id}
    public function forDoctor($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        $user = Auth::user();

        if ($user->hasAnyRole(['Admin','CRM Agent'])) {
            // ok
        } elseif ($user->hasRole('Doctor')) {
            // check that the authenticated doctor is requesting their own appointments
            $myDoctor = Doctor::where('user_id', $user->id)->first();
            if (! $myDoctor || $myDoctor->id !== $doctor->id) abort(403);
        } else {
            abort(403);
        }

        $appointments = Appointment::where('doctor_id', $doctor->id)->with('patient')->get();
        return response()->json($appointments);
    }

    // PUT/PATCH /api/appointments/{appointment_id} (Admin, CRM Agent, Doctor for own appointments)
    public function update(Request $request, $appointment_id)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $user = Auth::user();

        if ($user->hasAnyRole(['Admin','CRM Agent'])) {
            // ok
        } elseif ($user->hasRole('Doctor')) {
            $myDoctor = Doctor::where('user_id', $user->id)->first();
            if (! $myDoctor || $appointment->doctor_id !== $myDoctor->id) abort(403);
        } else {
            abort(403);
        }

        $data = $request->validate([
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'status' => 'sometimes|in:Scheduled,Confirmed,Completed,Cancelled,No-Show',
            'notes' => 'nullable|string',
        ]);

        // If rescheduling (date/time changed), check availability
        if ((isset($data['appointment_date']) && $data['appointment_date'] != $appointment->appointment_date) ||
            (isset($data['appointment_time']) && $data['appointment_time'] != $appointment->appointment_time)) {

            $date = $data['appointment_date'] ?? $appointment->appointment_date;
            $time = $data['appointment_time'] ?? $appointment->appointment_time;

            $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('appointment_date', $date)
                ->where('appointment_time', $time)
                ->where('id','!=',$appointment->id)
                ->whereIn('status', ['Scheduled','Confirmed'])
                ->exists();

            if ($conflict) {
                return response()->json(['message' => 'Doctor is not available at the selected date and time'], Response::HTTP_CONFLICT);
            }
        }

        $appointment->fill($data);
        $appointment->save();

        return response()->json($appointment);
    }

    // DELETE /api/appointments/{appointment_id} (Admin, CRM Agent)
    public function destroy($appointment_id)
    {
        $user = Auth::user();
        if (! $user->hasAnyRole(['Admin','CRM Agent'])) abort(403);

        $appointment = Appointment::findOrFail($appointment_id);
        $appointment->delete();

        return response()->json(['message' => 'Deleted'], Response::HTTP_NO_CONTENT);
    }
}
