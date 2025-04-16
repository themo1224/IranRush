<?php

namespace Modules\Ticket\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ticket\Database\factories\TicketReplyFactory;

class TicketReply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): TicketReplyFactory
    {
        //return TicketReplyFactory::new();
    }
}
