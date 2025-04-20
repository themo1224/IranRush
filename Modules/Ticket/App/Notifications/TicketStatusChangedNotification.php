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

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
    public function toMail($notifiable)
    {
        $subject = 'وضعیت تیکت شما بروزرسانی شد';
        $view = 'ticket.ticket_status';
        $data = [
            'ticket'    => $this->ticket,
            'oldStatus' => $this->oldStatus,
            'newStatus' => $this->newStatus,
            'url'       => url("/admin/tickets/{$this->ticket->id}"), // Dynamic URL
        ];
        return app(EmailService::class)->send(
            $notifiable->email,
            $subject,
            $view,
            $data
        );
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
