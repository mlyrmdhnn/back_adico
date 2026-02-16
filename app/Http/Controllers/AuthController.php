<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(Request $request)
    {

        $user = User::where('username', $request->username)->first();

        // if(!$user->deleted_at) {
        //     return response()->json([
        //         'status' => 'ok',
        //         'message' => 'Your account has been banned, please contact the administrator'
        //     ]);
        // }

        if(!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Incorrect username or password',
            ],401);
        }

        // $credentials = request(['username', 'password']);
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'deleted_at' => null
        ];

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json(['status' => 'ok'])
            ->withCookie(cookie()->forget('token'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        // return $this->respondWithToken(auth()->refresh());
        if(!$request->hasCookie('token')) {
            return response()->json(['message' => 'Unauthenticated'],401);
        }

        try {
            $newToken = auth()->refresh();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'status' => 'ok',
            'message' => 'success refresh'
        ])->withCookie(
            cookie(
                'token',
                $newToken,
                auth()->factory()->getTTL(),
                '/',
                null,
                true,
                true,
                false,
                'None'
            )
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            // 'token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ])->withCookie(
            cookie(
                'token',
                $token,
                auth()->factory()->getTTL(),
                '/',
                null,
                true,
                true,
                false,
                'None'
            )
        );
    }
}
