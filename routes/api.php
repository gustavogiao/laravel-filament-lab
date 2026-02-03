<?php

use App\Modules\Telemetry\Http\Controllers\TelemetryController;

Route::post('/telemetry/vital-signs', [TelemetryController::class, 'store'])
    ->middleware('device.auth');
