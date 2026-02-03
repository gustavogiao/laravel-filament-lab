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

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'heart_rate' => 'heart rate',
            'blood_pressure_systolic' => 'systolic blood pressure',
            'blood_pressure_diastolic' => 'diastolic blood pressure',
            'respiratory_rate' => 'respiratory rate',
            'body_temperature' => 'body temperature',
            'recorded_at' => 'recorded at',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'heart_rate.between' => 'Heart rate must be between 30 and 220 bpm.',
            'blood_pressure_systolic.between' => 'Systolic pressure must be between 70 and 200 mmHg.',
            'blood_pressure_diastolic.between' => 'Diastolic pressure must be between 40 and 130 mmHg.',
            'respiratory_rate.between' => 'Respiratory rate must be between 5 and 40 breaths/min.',
            'body_temperature.between' => 'Body temperature must be between 30ºC and 45ºC.',
        ];
    }
}
