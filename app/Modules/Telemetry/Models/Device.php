<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Models;

use App\Models\BaseModel;
use App\Modules\Telemetry\Policies\DevicePolicy;
use Carbon\CarbonInterface;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $device_uid
 * @property string $api_token_hash
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, PatientDeviceAssignment> $patientDeviceAssignments
 * @property-read Collection<int, Patient> $patients
 * @property-read Collection<int, VitalSignReading> $vitalSignReadings
 */
#[UsePolicy(DevicePolicy::class)]
#[UseFactory(DeviceFactory::class)]
final class Device extends BaseModel
{
    protected $table = 'devices';

    protected static function newFactory(): DeviceFactory
    {
        return DeviceFactory::new();
    }

    /**
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'device_uid' => 'string',
            'api_token_hash' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function patientAssignments(): HasMany
    {
        return $this->hasMany(PatientDeviceAssignment::class, 'device_id', 'id');
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            Patient::class,
            'patient_device_assignments',
            'device_id',
            'patient_id'
        )
            ->withPivot(['assigned_at', 'unassigned_at', 'is_active'])
            ->withTimestamps();
    }

    public function activePatient(): ?Model
    {
        return $this->patients()
            ->wherePivot('is_active', true)
            ->first();
    }

    public function vitalSignReadings(): HasMany
    {
        return $this->hasMany(VitalSignReading::class, 'device_id', 'id');
    }
}
