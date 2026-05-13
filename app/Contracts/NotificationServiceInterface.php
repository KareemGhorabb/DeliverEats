<?php

namespace App\Contracts;

use App\Models\User;

/**
 * Contract for sending notifications to users.
 *
 * TODO: Implement with Firebase Cloud Messaging, SMS, etc.
 * See EXTERNAL_INTEGRATIONS_GUIDE.md for setup instructions.
 */
interface NotificationServiceInterface
{
    /**
     * Send a push notification to a user.
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array<string, mixed> $data Extra payload
     * @return bool
     */
    public function sendPush(User $user, string $title, string $body, array $data = []): bool;

    /**
     * Send an SMS notification.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendSms(string $phone, string $message): bool;
}
