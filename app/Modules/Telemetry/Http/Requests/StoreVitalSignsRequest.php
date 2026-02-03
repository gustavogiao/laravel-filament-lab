<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreVitalSignsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'heart_rate' => ['nullable', 'numeric', 'between:30,220'],
            'blood_pressure_systolic' => ['nullable', 'numeric', 'between:70,200'],
            'blood_pressure_diastolic' => ['nullable', 'numeric', 'between:40,130'],
            'respiratory_rate' => ['nullable', 'numeric', 'between:5,40'],
            'body_temperature' => ['nullable', 'numeric', 'between:30,45'],
            'recorded_at' => ['nullable', 'date'],
        ];
    }
}
