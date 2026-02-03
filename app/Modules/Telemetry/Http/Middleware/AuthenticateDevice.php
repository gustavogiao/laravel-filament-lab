<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Middleware;

use App\Modules\Telemetry\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        abort_if(! $token, 401, 'Unauthorized: Missing device token');

        $device = Device::where('api_token_hash', hash('sha256', $token))->first();

        abort_if(! $device, 401, 'Unauthorized: Invalid device token');

        $request->attributes->set('device', $device);

        return $next($request);
    }
}
