<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Telemetry\Actions\RecordVitalSignsAction;
use App\Modules\Telemetry\Http\Requests\StoreVitalSignsRequest;
use Illuminate\Http\JsonResponse;

final class TelemetryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVitalSignsRequest $request, RecordVitalSignsAction $action): JsonResponse
    {
        $device = $request->attributes->get('device');

        $reading = $action->execute($device, $request->validated());

        return response()->json([
            'message' => 'Vital signs recorded successfully',
            'data' => $reading,
        ], 201);
    }
}
