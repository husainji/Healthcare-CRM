<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class PatientAudit extends Model
{
    protected $table = 'patient_audits';

    protected $fillable = [
        'user_id',
        'patient_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // helper to create audit record from model events
    public static function createFromModelEvent(string $action, $patient, $oldValues = null)
    {
        $user = Auth::user();
        $ip = Request::ip();

        $new = $patient->getAttributes();

        // Normalize arrays for JSON columns:
        $old = $oldValues ? $oldValues : null;

        static::create([
            'user_id' => $user ? $user->id : null,
            'patient_id' => $patient->id,
            'action' => $action,
            'old_values' => $old,
            'new_values' => $action === 'deleted' ? null : $new,
            'ip_address' => $ip,
        ]);
    }
}
