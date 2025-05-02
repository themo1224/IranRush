<?php

namespace Modules\Email\App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\Email\App\Contracts\EmailServiceInterface;
use Modules\Email\App\Exceptions\EmailSendingException;
use Modules\Email\App\Mail\BaseMail;

class EmailService implements EmailServiceInterface
{
    /**
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;

    public function __construct()
    {
        $this->mailer = Mail::getFacadeRoot();
    }
    /**
     * Send an email immediately
     */
    public function send($to, string $subject, string $view, array $data = [], array $attachments = []): bool
    {
        try {
            if (!view()->exists($view)) {
                throw new \Exception("View '{$view}' does not exist");
            }
            $mail = new BaseMail($subject, $view, $data, $attachments);
            $mail->from(
                Config::get('email.form.address'),
                Config::get('email.from.name'),
            );

            $attempts = Config::get('email.retry.attempts', 1);
            $delay = Config::get('email.retry.delay', 5);

            for ($attempt = 1; $attempt <= $attempts; $attempts++) {
                try {
                    $this->mailer->to($to)->send($mail);
                    $this->logSuccess($to, $subject, $view);
                    return true;
                } catch (\Exception $e) {
                    if ($attempt === $attempts) {
                        throw $e;
                    }
                    sleep($delay);
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->logError($to, $subject, $e->getMessage());
            throw new EmailSendingException(
                message: "Failed to send email: " . $e->getMessage(),
                context: [
                    'recipient' => $to,
                    'subject' => $subject,
                    'view' => $view,
                    'error' => $e->getMessage()
                ],
                previous: $e
            );
        }
    }

    /**
     * Queue an email for sending
     */
    public function queue($to, string $subject, string $view, array $data = [], array $attachments = []): bool
    {
        try {
            if (!Config::get('email.queue.enabled', true)) {
                return $this->send($to, $subject, $view, $data, $attachments);
            }

            $mail = new BaseMail($subject, $view, $data, $attachments);
            $mail->from(
                Config::get('email.from.address'),
                Config::get('email.from.name')
            );

            $this->mailer
                ->to($to)
                ->queue($mail, Config::get('email.queue.queue'));

            $this->logSuccess($to, $subject, $view, true);
            return true;

        } catch (\Exception $e) {
            $this->logError($to, $subject, $e->getMessage());
            return false;
        }
    }

    /**
     * Get the mailer instance
     */
    public function mailer() {
        return $this->mailer();
    }

    /**
     * Log a successful email
     */
    protected function logSuccess($to, string $subject, string $view, bool $queued = false )
    {
        if (!Config::get('email.logging.enabled', true)) {
            return ;
        }

        $type = $queued ? 'queued' : 'sent';
        Log::channel(Config::get('email.logging.channel', 'email'))
            ->info("Email {$type} successfully", [
                'to' => $to,
                'subject' => $subject,
                'view' => $view
            ]);
    }

    /**
     * Log an email error
     */
    protected function logError($to, string $subject, string $error): void
    {
        if (!Config::get('email.logging.enabled', true)) {
            return;
        }

        Log::channel(Config::get('email.logging.channel', 'email'))
            ->error("Email sending failed", [
                'to' => $to,
                'subject' => $subject,
                'error' => $error
            ]);
    }

}
