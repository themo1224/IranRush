<?php

namespace Modules\Ticket\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ticket\App\Http\Requests\CreateTicketRequest;
use Modules\Ticket\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function store(CreateTicketRequest $request)
    {
        $ticket = $this->ticketService->create([
            'subject' => $request->subject,
            'message' => $request->message,
            'user_id' => auth()->user()->id,
            'media' => $request->file('attachment'),
        ]);

        return response()->json([
            'message' => 'تیکت با موفقیت ثبت شد',
            'data' => $ticket,
        ], 201);
    }
}
