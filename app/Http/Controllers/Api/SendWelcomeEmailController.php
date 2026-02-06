<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        $emails = $user->emails()->pluck('email')->toArray();

        if (empty($emails)) {
            return response()->json(['message' => 'User has no email addresses'], 422);
        }

        Mail::to($emails)->send(new WelcomeUserMail($user));

        return response()->json(['message' => 'Emails sent successfully']);
    }
}
