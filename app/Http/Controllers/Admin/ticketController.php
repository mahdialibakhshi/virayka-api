<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\changeStatusTicket;
use App\Notifications\ticketReplay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ticketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('parent', '=', 0)->latest()->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $id)
    {
        $ticket = $id;
        $status = Status::all();
        $conversation = Ticket::where('parent', $ticket->id)
            ->get();
        return view('admin.tickets.show', compact('ticket', 'status', 'conversation'));
    }

    public function changeStatusAjax(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        $ticket->update([
            'status_id' => $request->status_id,
        ]);
        $user = User::find($ticket->user_id);
        $status=Status::find($request->status_id)->title;
        $user->notify(new changeStatusTicket($status));
        return response()->json([1]);
    }

    public function replay(Request $request)
    {
        $request->validate([
            'description' => 'required|max:60000',
        ]);
        $ticket = Ticket::find($request->ticket_id);
        $ticket->update([
            'status_id' => 3,
        ]);
        Ticket::create([
            'description' => $request->description,
            'parent' => $request->ticket_id,
            'user_id' => 'admin',
            'title' => $ticket->title,
            'status_id' => 3,
        ]);
        $ticket->update([
            'status_id' => 3,
        ]);
        //GET USER INFO
        $user = User::find($request->user_id);
        $user->notify(new ticketReplay($ticket->id));

        alert()->success('پاسخ با موفقیت ارسال شد', 'با تشکر');
        return redirect()->back();
    }
}
