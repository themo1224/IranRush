<?php

namespace Modules\Ticket\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ticket\Database\factories\TicketReplyFactory;

class TicketReply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['admin_id', 'message', 'media_id', 'ticket_id'];
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
