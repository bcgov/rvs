<?php

namespace App\Http\Middleware;

use Exception;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):((Response | RedirectResponse)) $next
     * @param  string|null  ...$roles
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles): Response|RedirectResponse|JsonResponse
    {
        $forwardedHost = $request->headers->get('X-Forwarded-Host');
        \Log::info('Forwarded Host: ' . $forwardedHost);

        // Prevent access to the API except via the gateway
        if ($forwardedHost !== env('KEYCLOAK_APS_URL')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = request()->bearerToken();
        if(is_null($token)){
            return response()->json(['status' => false, 'error' => 'Missing token.'], 401);
        }
        $jwksUri = env('KEYCLOAK_APS_ISS') . env('KEYCLOAK_APS_CERT_PATH');
        $jwksJson = file_get_contents($jwksUri);
        $jwksData = json_decode($jwksJson, true);
        $matchingKey = null;
        foreach ($jwksData['keys'] as $key) {
            if (isset($key['use']) && $key['use'] === 'sig') {
                $matchingKey = $key;
                break;
            }
        }

        $wrappedPk = wordwrap((string) $matchingKey['x5c'][0], 64, "\n", true);
        $pk = "-----BEGIN CERTIFICATE-----\n" . $wrappedPk . "\n-----END CERTIFICATE-----";

        try {
            $decoded = JWT::decode($token, new Key($pk, 'RS256'));
        } catch (ExpiredException) {
            return response()->json(['status' => false, 'error' => 'Token has expired.'], 401);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => "An error occurred: " . $e->getMessage()], 401);
        }
            //only validate for accounts that we have registered
        if (isset($decoded->iss) && $decoded->iss === env('KEYCLOAK_APS_ISS')) {
//                $user = ServiceAccount::where('client_id', $decoded->clientId)->first();
//                if(!is_null($user)){
//                    if($user->active)
                        return $next($request);
//                }
        }

        return response()->json(['status' => false, 'error' => "Generic error."], 403);
    }
}
