<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $patientId = $this->route('patient_id');

        // find patient record by patient_id
        // We'll validate unique using id lookup - find patient by patient_id
        $patient = \App\Models\Patient::where('patient_id', $patientId)->first();

        $ignorePhone = $patient ? $patient->id : null;
        $ignoreEmail = $patient ? $patient->id : null;

        return [
            'user_id' => 'sometimes|required',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:Male,Female,Other',
            'phone_number' => [
                'sometimes','required','string','max:50',
                Rule::unique('patients','phone_number')->ignore($ignorePhone),
            ],
            'email' => [
                'nullable','email',
                Rule::unique('patients','email')->ignore($ignoreEmail),
            ],
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'insurance_details' => 'nullable|array',
        ];
    }
}
