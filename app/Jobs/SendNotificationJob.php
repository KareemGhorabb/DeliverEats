<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param  User    $recipient  The user to notify
     * @param  string  $subject    Notification subject / title
     * @param  string  $message    Notification body
     */
    public function __construct(
        public readonly User   $recipient,
        public readonly string $subject,
        public readonly string $message,
    ) {
        // Route all notifications to a dedicated Redis queue
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     * Currently logs the notification; extend this to send emails, push notifications, etc.
     */
    public function handle(): void
    {
        // ── Log notification (always) ──────────────────────────────────────────
        Log::info('SendNotificationJob: Delivering notification', [
            'recipient_id'    => $this->recipient->id,
            'recipient_email' => $this->recipient->email,
            'subject'         => $this->subject,
            'message'         => $this->message,
        ]);

        // ── Email notification ─────────────────────────────────────────────────
        // MAIL_MAILER is set to 'log' in development, so this will go to laravel.log.
        // Change MAIL_MAILER to smtp/ses/etc. in production to actually send emails.
        try {
            Mail::raw($this->message, function ($mail) {
                $mail->to($this->recipient->email, $this->recipient->name)
                     ->subject("[DeliverEats] {$this->subject}");
            });
        } catch (\Exception $e) {
            // Log the failure but don't crash the job — notification delivery is non-critical
            Log::warning('SendNotificationJob: Failed to send email notification', [
                'recipient_id' => $this->recipient->id,
                'error'        => $e->getMessage(),
            ]);
        }
    }
}

