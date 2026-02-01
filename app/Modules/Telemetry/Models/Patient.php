<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Models;

use App\Models\BaseModel;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $date_of_birth
 * @property string $gender
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Collection<int, PatientDeviceAssignment> $deviceAssignments
 * @property-read Collection<int, Device> $devices
 * @property-read Collection<int, VitalSignReading> $vitalSignReadings
 */
final class Patient extends BaseModel
{
    protected $table = 'patients';

    protected static function newFactory(): PatientFactory
    {
        return PatientFactory::new();
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
            'first_name' => 'string',
            'last_name' => 'string',
            'date_of_birth' => 'date',
            'gender' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function deviceAssignments(): HasMany
    {
        return $this->hasMany(PatientDeviceAssignment::class);
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(
            Device::class,
            'patient_device_assignments'
        )
            ->withPivot(['assigned_at', 'unassigned_at', 'is_active'])
            ->withTimestamps();
    }

    public function vitalSignReadings(): HasMany
    {
        return $this->hasMany(VitalSignReading::class, 'patient_id', 'id');
    }

    public function activeDevice(): ?Device
    {
        $device = $this->devices()
            ->wherePivot('is_active', true)
            ->first();

        return $device instanceof Device ? $device : null;
    }

    public function hasActiveDevice(): bool
    {
        return $this->devices()
            ->wherePivot('is_active', true)
            ->exists();
    }
}
