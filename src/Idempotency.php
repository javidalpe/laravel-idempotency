<?php

namespace Javidalpe\Idempotency;

use Closure;
use Illuminate\Support\Facades\Cache;

class Idempotency
{
    const IDEMPOTENCY_HEADER = "Idempotency-Key";
    const EXPIRATION_IN_MINUTES = 60*24;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'GET' || $request->method() == 'DELETE') {
            return $next($request);
        }

        $requestId = $request->header(self::IDEMPOTENCY_HEADER);
        if (!$requestId) {
            return $next($request);
        }

        if (Cache::has($requestId)) {
            return Cache::get($requestId);
        }

        $response = $next($request);
        $response->header(self::IDEMPOTENCY_HEADER, $requestId);
        Cache::put($requestId, $response, self::EXPIRATION_IN_MINUTES);
        return $response;
    }
}
