<?php

namespace Modules\Ticket\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Email\App\Services\EmailService;
use Modules\Ticket\App\Models\Ticket;

class TicketStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $emailService;


    /**
     * Create a new notification instance.
     */
    protected $ticket;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus, EmailService $emailService)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->emailService = $emailService;

    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('وضعیت تیکت شما بروزرسانی شد')
            ->line('تیکت: ' . $this->ticket->subject)
            ->line('وضغیت قبلی: ' . $this->oldStatus)
            ->line('وضعیت جدید: ' . $this->newStatus)
            ->action('دیدن تیکت', env('Admin-Url').'/tickets/' . $this->ticket->id)
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Ticket status has been updated',
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
