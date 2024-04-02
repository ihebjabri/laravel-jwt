<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SendEmailVerificationNotificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/send-email-verification-notification",
     *     summary="Send Email Verification Notification",
     *     description="Send the email verification notification to the user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Email verification notification sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="verification-link-sent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    
    public function __invoke(): JsonResponse
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'email-already-verified',
            ]);
        }

        Auth::user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'verification-link-sent',
        ]);
    }
}
