<?php

namespace Modules\Ticket\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Ticket\App\Http\Requests\CreateTicketRequest;
use Modules\Ticket\App\Models\Ticket;
use Modules\Ticket\Events\TicketStatusChanged;
use Modules\Ticket\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function store(Request $request)
    {
        try {
            $ticket = $this->ticketService->create([
                'subject' => $request->subject,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'media' => $request->file('attachment'),
            ]);
    
            return response()->json([
                'message' => 'تیکت با موفقیت ثبت شد',
                'data' => $ticket,
            ], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function replyTicket(Request $request)
    {
        return DB::transaction(function () use ($request){
            try {
                $replyTicket = $this->ticketService->replyTicket($request->all());
                
                return response()->json([
                    'message' => 'Reply added successfully',
                    'data' => $replyTicket
                ]);
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()], 500);
            }
        });
    }

}
