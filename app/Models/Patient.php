<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\PatientAudit;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone_number',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_details',
    ];

    protected $casts = [
        'insurance_details' => 'array',
        'date_of_birth' => 'date',
    ];

    // generate patient_id automatically
    protected static function booted()
    {
        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                // Example format: PAT-<random 8>
                $patient->patient_id = 'PAT-' . strtoupper(Str::random(8));
            }
        });

        // Audit: created
        static::created(function ($patient) {
            PatientAudit::createFromModelEvent('created', $patient, null);
        });

        // Audit: updated
        static::updating(function ($patient) {
            $original = $patient->getOriginal();
            // We'll store original in a temporary attribute; actual audit created on updated.
            $patient->temp_original = $original;
        });

        static::updated(function ($patient) {
            $old = $patient->temp_original ?? [];
            PatientAudit::createFromModelEvent('updated', $patient, $old);
        });

        static::deleted(function ($patient) {
            $old = $patient->getOriginal();
            PatientAudit::createFromModelEvent('deleted', $patient, $old);
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audits()
    {
        return $this->hasMany(PatientAudit::class);
    }
}
