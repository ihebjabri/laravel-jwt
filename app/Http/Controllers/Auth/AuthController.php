<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Users\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
      /**
     * Register
     * @OA\Post (
     *     path="/api/register",
     *     description="register user and return success message",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password_confirmation",
     *                          type="string"
     *                      )
     * 
     *                 ),
     *                 example={
     *                     "name":"Iheb",
     *                     "email":"iheb@email.com",
     *                     "password": "iheb1234",
     *                     "password_confirmation":"iheb1234"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="success"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function register(RegisterRequest $request, CreateUser $createUser): JsonResponse
    {
        $createUser(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );

        return response()->json([
            'status' => 'user-created',
        ]);
    }

     /**
     * Login
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login",
     *     description="Authenticate user and return access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="invalid-credentials")
     *         )
     *     )
     * )
     */

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);

        if (! $token) {
            return response()->json([
                'status' => 'invalid-credentials',
            ], 401);
        }

        return response()->json([
            'user'         => new UserResource(Auth::user()),
            'access_token' => $token,
            'token_type' => 'bearer', 
            'expires_in' => auth()->factory()->getTTl()*60,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout",
     *     description="Logout the authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Successfully logged out",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */

    public function logout(): Response
    {
        Auth::logout();

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Refresh Token",
     *     description="Refresh the access token",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful token refresh",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */


    public function refresh(): JsonResponse
    {
        $token = Auth::refresh();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer', 
            'expires_in' => auth()->factory()->getTTl()*60,
        ]);
    }

}
