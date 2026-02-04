<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Models;

use App\Models\BaseModel;
use App\Modules\Telemetry\Policies\VitalSignReadingPolicy;
use Database\Factories\VitalSignReadingFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $patient_id
 * @property string $device_id
 * @property Carbon $recorded_at
 * @property float|null $heart_rate
 * @property float|null $blood_pressure_systolic
 * @property float|null $blood_pressure_diastolic
 * @property float|null $respiratory_rate
 * @property float|null $body_temperature
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Patient $patient
 * @property-read Device $device
 */
#[UsePolicy(VitalSignReadingPolicy::class)]
#[UseFactory(VitalSignReadingFactory::class)]
final class VitalSignReading extends BaseModel
{
    protected $table = 'vital_sign_readings';

    protected static function newFactory(): VitalSignReadingFactory
    {
        return VitalSignReadingFactory::new();
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
            'recorded_at' => 'datetime',
            'heart_rate' => 'decimal:2',
            'blood_pressure_systolic' => 'decimal:2',
            'blood_pressure_diastolic' => 'decimal:2',
            'respiratory_rate' => 'decimal:2',
            'body_temperature' => 'decimal:2',
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
