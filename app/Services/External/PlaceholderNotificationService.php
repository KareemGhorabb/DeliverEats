<?php

namespace App\Services\External;

use App\Contracts\NotificationServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Placeholder notification service — logs instead of sending.
 *
 * TODO: Replace with Firebase Cloud Messaging / Twilio / etc.
 *
 * @see EXTERNAL_INTEGRATIONS_GUIDE.md
 */
class PlaceholderNotificationService implements NotificationServiceInterface
{
    public function sendPush(User $user, string $title, string $body, array $data = []): bool
    {
        // TODO: Implement with FCM or similar push service
        Log::info('Push Notification', [
            'user_id' => $user->id,
            'title'   => $title,
            'body'    => $body,
            'data'    => $data,
        ]);

        return true;
    }

    public function sendSms(string $phone, string $message): bool
    {
        // TODO: Implement with Twilio or similar SMS service
        Log::info('SMS Notification', [
            'phone'   => $phone,
            'message' => $message,
        ]);

        return true;
    }
}
