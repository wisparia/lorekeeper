<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Config;
use Illuminate\Http\Request;

use App\Models\Prompt\PromptCategory;
use App\Models\HelpTicket\HelpTicket;
use App\Models\Item\Item;
use App\Models\Currency\Currency;
use App\Models\Loot\LootTable;


use App\Services\HelpTicketManager;

use App\Http\Controllers\Controller;

class HelpTicketController extends Controller
{
    /**
     * Shows the helpticket index page.
     *
     * @param  string  $status
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHelpTicketIndex(Request $request, $status = null)
    {
        if($status == 'assigned-to-me') $helpTickets = HelpTicket::assignedToMe(Auth::user()); 
        else $helpTickets = HelpTicket::where('status', $status ? ucfirst($status) : 'Pending');

        return view('admin.helptickets.index', [
            'helpTickets' => $helpTickets->orderBy('id', 'DESC')->paginate(30)->appends($request->query()),
        ]);
    }
    
    /**
     * Shows the helpticket detail page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHelpTicket($id)
    {
        $helpTicket = HelpTicket::where('id', $id)->first();
        if(!$helpTicket) abort(404);
        return view('admin.helptickets.report', [
            'helpTicket' => $helpTicket,
        ]);
    }    

    /**
     * Creates a new helpticket.
     *
     * @param  \Illuminate\Http\Request        $request
     * @param  App\Services\HelpTicketManager  $service
     * @param  int                             $id
     * @param  string                          $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postHelpTicket(Request $request, HelpTicketManager $service, $id, $action)
    {
        $data = $request->only(['staff_comments']);
        if($action == 'assign' && $service->assignHelpTicket($request->only(['staff_comments']) + ['id' => $id], Auth::user())) {
            flash('Help ticket assigned successfully.')->success();
        }
        elseif($action == 'close' && $service->closeHelpTicket($data + ['id' => $id], Auth::user())) {
            flash('Help ticket closed successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
