<?php

namespace App\Http\Middleware;

use App\Models\App;
use App\Models\AuthSecret;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $app = App::where('patreon_id', $id)
            ->first();
        
        if (!$app) {
            return response()->json(['message' => 'App Not Found'], 404);
        }

        $appSecret = AuthSecret::where('app_id', $app->id)
            ->first();

        if ($request->header('Authorization') !== $appSecret->client_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
