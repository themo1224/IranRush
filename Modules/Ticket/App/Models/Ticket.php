<?php

namespace Modules\Ticket\App\Models;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Ticket\App\Models\TicketReply;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the parent ticket if this is a reply.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Get the replies for the ticket.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    /**
     * Get all of the ticket's media.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}