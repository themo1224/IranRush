<?php

namespace Modules\Ticket\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Email\App\Services\EmailService;
use Modules\Ticket\App\Models\Ticket;

class TicketCreatedNotification extends Notification 
{
    use Queueable;

    protected $ticket;
    protected $emailService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
            ->subject('تیکتی ساخته شده است سکسی')
            ->markdown('ticket.ticket_created', [
                'ticket' => $this->ticket,
                'url' => url("/admin/tickets/{$this->ticket->id}")
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'description' => $this->ticket->description,
            'subject' => $this->ticket->subject,
            'url' => env('ADMIN_URLl') . '/tickets/' . $this->ticket->id,
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
