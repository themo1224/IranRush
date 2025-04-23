<?php

namespace Modules\Ticket\Services;

use App\Traits\HandlesMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Ticket\App\Models\Ticket;
use Modules\Ticket\App\Models\TicketReply;
use Modules\Ticket\App\Events\TicketCreated;
use Modules\Ticket\Events\TicketStatusChanged;

class TicketService
{
    use HandlesMedia;

    public function create(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create([
                'user_id' => $data['user_id'],
                'subject' => $data['subject'],
                'description' => $data['description'],
                'status' => $data['status'] ?? 'open',
            ]);

            // Handle media attachments if present
            $this->handleMedia($ticket, $data['media'] ?? null, 'tickets');

            // Dispatch the TicketCreated event
            event(new TicketCreated($ticket));

            return $ticket;
        });
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($ticket, $data) {
            $ticket->update([
                'subject' => $data['subject'] ?? $ticket->subject,
                'description' => $data['description'] ?? $ticket->description,
                'status' => $data['status'] ?? $ticket->status,
            ]);

            return $ticket;
        });
    }

    public function delete(Ticket $ticket): bool
    {
        return DB::transaction(function () use ($ticket) {
            // Delete associated media files
            foreach ($ticket->media as $media) {
                Storage::delete($media->path);
                $media->delete();
            }

            return $ticket->delete();
        });
    }

    public function replyTicket(array $data)
    {
        return DB::transaction(function () use ($data){
            $adminID= Auth::id();
            $ticket = Ticket::find($data['ticket_id']);
            $old_status= $ticket->status;
            
            $ticketReply= TicketReply::create([
                'ticket_id' => $data['ticket_id'],
                'admin_id' => $adminID,
                'description' => $data['description'], 
            ]);
            
            $this->handleMedia($ticketReply, $data['media'] ?? null, 'tickets');
            
            // Update ticket status to 'answered' when admin replies
            $this->updateStatus($ticket, 'answered');
            
            return $ticketReply;
        });
    }

    public function updateStatus(Ticket $ticket, string $newStatus)
    {
        return DB::transaction(function() use($ticket, $newStatus){
            $oldStatus = $ticket->status;
            $ticket->update(['status' => $newStatus]);
            event(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
            return $ticket;
        });
    }
}
