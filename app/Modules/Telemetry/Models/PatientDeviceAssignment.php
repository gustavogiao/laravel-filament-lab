<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Models;

use App\Models\BaseModel;
use Database\Factories\PatientDeviceAssignmentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $patient_id
 * @property string $device_id
 * @property Carbon|null $assigned_at
 * @property Carbon|null $unassigned_at
 * @property bool $is_active
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Patient $patient
 * @property-read Device $device
 */
final class PatientDeviceAssignment extends BaseModel
{
    protected $table = 'patient_device_assignments';

    protected static function newFactory(): PatientDeviceAssignmentFactory
    {
        return PatientDeviceAssignmentFactory::new();
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
            'patient_id' => 'string',
            'device_id' => 'string',
            'assigned_at' => 'datetime',
            'unassigned_at' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}
