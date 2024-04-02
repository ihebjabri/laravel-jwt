<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/verify-email",
     *     summary="Verify Email",
     *     description="Verify the user's email",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="email-verified")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Email already verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="email-already-verified")
     *         )
     *     )
     * )
     */
    
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'email-already-verified',
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'status' => 'email-verified',
        ]);
    }
}
