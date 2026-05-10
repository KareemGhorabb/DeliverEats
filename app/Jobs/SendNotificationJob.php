<?php

namespace App\Jobs;

use App\Contracts\NotificationServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $userId,
        public readonly string $title,
        public readonly string $body,
        public readonly array $data = [],
    ) {
        $this->onQueue('notifications');
    }

    public function handle(NotificationServiceInterface $notifications): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        $notifications->sendPush($user, $this->title, $this->body, $this->data);
    }
}
