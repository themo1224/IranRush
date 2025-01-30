<?php

namespace Modules\Notification\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AssetUploadedNotification extends Notification
{
    // use Queueable;

    protected $mediaType;
    protected $mediaId;

    /**
     * Create a new notification instance.
     */
    public function __construct($mediaType, $mediaId)
    {
        $this->mediaId = $mediaId;
        $this->mediaType = $mediaType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database']; //use database notification
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail($notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', 'https://laravel.com')
    //         ->line('Thank you for using our application!');
    // }


    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'media_type' => $this->mediaType,
            'media_id' => $this->mediaId,
            'message' => "A new {$this->mediaType} has been uploaded.",
        ];
    }
}
