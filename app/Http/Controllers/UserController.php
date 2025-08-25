<?php

namespace App\Http\Controllers;

use RuntimeException;
use Exception;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Requests\AjaxRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class UserController extends Controller
{
    /**
     * Display first page after login (dashboard page)
     */
    public function home(Request $request): Response {
        return Inertia::render('Home');
    }

    public function appLogin(Request $request): Response|RedirectResponse {
        $provider = new Keycloak([
            'authServerUrl' => env('KEYCLOAK_SERVER_URL'),
            'realm' => env('KEYCLOAK_REALM'),
            'clientId' => env('KEYCLOAK_CLIENT_ID'),
            'clientSecret' => env('KEYCLOAK_CLIENT_SECRET'),
            'redirectUri' => env('KEYCLOAK_REDIRECT_URI'),
            'version' => '24.0.2', # Add this parameter to troubleshoot the issue

        ]);

        if (! $request->has('code')) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $request->session()->put('oauth2state', $provider->getState());

            return Redirect::to($authUrl);

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (! $request->has('state') || ($request->state !== $request->session()->get('oauth2state'))) {
            $request->session()->forget('oauth2state');

            //Invalid state, make sure HTTP sessions are enabled
            return Inertia::render('Home', [
                'auth' => [
                    'user' => auth()->user() ? [
                        'id' => auth()->user()->id,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'email' => auth()->user()->email,
                    ] : null,
                    'roles' => auth()->user() ? auth()->user()->roles()->get() : [],
                ],
            ]);
        } else {
            // Try to get an access token (using the authorization coe grant)
            try {
                /** @var AccessTokenInterface $token */
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);

                if (!$token instanceof AccessToken) {
                    throw new RuntimeException('Expected AccessToken instance');
                }

            } catch (Exception $e) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Failed to get access token: '.$e->getMessage(),
                ]);
            }

            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the user's details
                $idir_user = $provider->getResourceOwner($token);
                $idir_user = $idir_user->toArray();
            } catch (Exception $e) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Failed to get resource owner: '.$e->getMessage(),
                ]);
            }

            $user = User::where('idir_user_guid', 'ilike', $idir_user['idir_user_guid'])->first();

            //if it is a new IDIR user, register the user first
            if (is_null($user)) {
                $this->newUser($idir_user);

                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Please contact Admin to grant you access.',
                ]);

                //if the user has been disabled
            } elseif ($user->disabled === true) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Access denied. Please contact Admin.',
                ]);
            }

            //else the user has access
            Auth::login($user);

            return Redirect::route('home');
        }
    }

    /**
     * fetch active support users
     */
    public function activeUsers(AjaxRequest $request): JsonResponse
    {
        $users = User::whereEndDate(null)->whereDisabled(false)->get();

        return response()->json(['status' => true, 'users' => $users]);
    }

    /**
     * fetch cancelled support users
     */
    public function cancelledUsers(AjaxRequest $request): JsonResponse
    {
        $users = User::where('end_date', '!=', null)->whereDisabled(true)->get();

        return response()->json(['status' => true, 'users' => $users]);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function dashboard(Request $request): Response {
        return Inertia::render('Yeaf/Students');
    }

    /**
     * Display the login view.
     *
     * @return Response
     */
    public function login(Request $request): Response {
        return Inertia::render('Auth/Login', [
            'loginAttempt' => false,
            'hasAccess' => false,
            'status' => session('status'),
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(Request $request): Redirector|Application|RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * @param array<string, mixed> $idir_user
     */
    private function newUser(array $idir_user): void {
        $user = User::where('user_id', 'ilike', $idir_user['idir_username'])->first();
        if (is_null($user)) {
            $user = new User();
            $user->user_id = Str::upper($idir_user['idir_username']);
            $user->first_name = $idir_user['given_name'];
            $user->last_name = $idir_user['family_name'];
            $user->email = $idir_user['email'];
        }
        $user->disabled = false;
        $user->idir_user_guid = $idir_user['idir_user_guid'];
        $user->password = Hash::make($idir_user['idir_username']);
        $user->save();

    }

    
    public function pdexLogin(Request $request): Response|RedirectResponse 
    {
        
        //if any of the formData keys are missing don't login the user
        $token = $request->input('token');
        $refreshToken = $request->input('refresh_token');
        $userType = $request->input('user_type');
        $userId = $request->input('ud');
        $logoutUrl = $request->input('logoutUrl');
        \Log::info('pdexLogin called with userType: ' . $userType . ', userId: ' . $userId . ', logoutUrl: ' . $logoutUrl);
        \Log::info('Received request: ' . json_encode($request->all()));

        if (empty($token) || empty($userType) || empty($userId) || empty($logoutUrl)) {
            return response()->json(['error' => 'Missing data 2239'], 400);
        }

        // Proceed with the login logic using the validated formData
        $decodedToken = $this->decodeJWT($token);
        if (isset($decodedToken['error'])) {
            return response()->json(['error' => $decodedToken['error']], 400);
        }
        if (empty($decodedToken['payload']['sub'])) {
            return response()->json(['error' => 'Missing data 2242'], 400);
        }
        if($decodedToken['payload']['aud'] !== env('PDEX_JWT_AUDIENCE')) {
            \Log::error('Invalid audience: ' . $decodedToken['payload']['aud']);
            return response()->json(['error' => 'Missing data 2243'], 400);
        }
        \Log::info('Decoded JWT Token: ' . json_encode($decodedToken));

        $idir_user = $decodedToken['payload'];
        $user = User::where('idir_user_guid', 'ilike', $idir_user['idir_user_guid'])->first();

        //if it is a new IDIR user, register the user first
        if (is_null($user)) {
            $this->newUser($idir_user);

            return Inertia::render('Auth/Login', [
                'loginAttempt' => true,
                'hasAccess' => false,
                'status' => 'Please contact Admin to grant you access.',
            ]);

            //if the user has been disabled
        } elseif ($user->disabled === true) {
            return Inertia::render('Auth/Login', [
                'loginAttempt' => true,
                'hasAccess' => false,
                'status' => 'Access denied. Please contact Admin.',
            ]);
        }

        //else the user has access
        Auth::login($user);

        return Redirect::route('home');
    
    }
    
    // Decode JWT token to see its contents (without verification for debugging)
    private function decodeJWT($token)
    {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) === 3) {
            try {
                // Decode the payload (second part)
                $payload = json_decode(base64_decode(str_pad(strtr($tokenParts[1], '-_', '+/'), strlen($tokenParts[1]) % 4, '=', STR_PAD_RIGHT)), true);
                
                // Decode the header (first part)
                $header = json_decode(base64_decode(str_pad(strtr($tokenParts[0], '-_', '+/'), strlen($tokenParts[0]) % 4, '=', STR_PAD_RIGHT)), true);
                
                $tokenInfo = [
                    'header' => $header,
                    'payload' => $payload,
                    'raw_token_length' => strlen($token),
                    'token_parts_count' => count($tokenParts)
                ];
            } catch (\Exception $e) {
                \Log::error('Failed to decode JWT token: ' . $e->getMessage());
                $tokenInfo = [
                    'error' => 'Missing data 2240',
                    'raw_token_length' => strlen($token)
                ];
            }
        } else {
            \Log::error('Invalid JWT format');
            $tokenInfo = [
                'error' => 'Missing data 2241',
                'token_parts_count' => count($tokenParts),
                'raw_token_length' => strlen($token)
            ];
        }

        return $tokenInfo;
    }
}
