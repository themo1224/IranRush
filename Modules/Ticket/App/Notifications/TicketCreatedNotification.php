<?php

namespace Modules\Ticket\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Email\App\Services\EmailService;
use Modules\Ticket\App\Models\Ticket;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $emailService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, EmailService  $emailService)
    {
        $this->ticket = $ticket;
        $this->emailService = $emailService; // Inject the service

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
        $subject = 'تیکتی ساخته شده است';
        $view = 'ticket.ticket_status';
        $data = [
            'ticket'    => $this->ticket,
            'url'       => url("/admin/tickets/{$this->ticket->id}"), // Dynamic URL
        ];
        return $this->emailService->send(
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
            'description' => $this->ticket->description,
            'message' => $this->ticket->message,
            'url' => env('Admin-Url').'/tickets/' . $this->ticket->id,
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
