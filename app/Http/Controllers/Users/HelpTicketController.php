<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;

use DB;
use Auth;
use Settings;
use App\Models\User\User;
use App\Models\Character\Character;
use App\Models\Item\Item;
use App\Models\Currency\Currency;
use App\Models\HelpTicket\HelpTicket;
use App\Models\Prompt\Prompt;

use App\Services\HelpTicketManager;

use App\Http\Controllers\Controller;

class HelpTicketController extends Controller
{
    /**********************************************************************************************

        helpticketS

    **********************************************************************************************/

    /**
     * Shows the user's helpticket log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHelpTicketsIndex(Request $request)
    {
        $helpTickets = HelpTicket::where('user_id', Auth::user()->id);
        $type = $request->get('type');
        if(!$type) $type = 'Pending';

        $helpTickets = $helpTickets->where('status', ucfirst($type));

        return view('home.help_tickets', [
            'helpTickets' => $helpTickets->orderBy('id', 'DESC')->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows the bug helpticket log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHelpTicketIndex(Request $request)
    {
        $helpTickets = HelpTicket::where('is_br', 1);

        $data = $request->only(['url']);

        if(isset($data['url']))
            $helpTickets->where('url', 'LIKE', '%'.$data['url'].'%');

        return view('home.help_ticket_index', [
            'helpTickets' => $helpTickets->orderBy('id', 'DESC')->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows the helpticket page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHelpTicket($id)
    {
        $helpTicket = HelpTicket::viewable(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if(!$helpTicket) abort(404);
        return view('home.help_ticket', [
            'helpTicket' => $helpTicket,
            'user' => $helpTicket->user
        ]);
    }

    /**
     * Shows the submit helpticket page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getNewHelpTicket(Request $request)
    {
        $closed = !Settings::get('is_helptickets_open');
        return view('home.create_helpticket', [
            'closed' => $closed,
        ]);
    }

    /**
     * Creates a new helpticket.
     *
     * @param  \Illuminate\Http\Request        $request
     * @param  App\Services\HelpTicketManager  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewHelpTicket(Request $request, HelpTicketManager $service)
    {
        $request->validate(HelpTicket::$createRules);
        $request['url'] = strip_tags($request['url']);

        if($service->createHelpTicket($request->only(['url', 'comments', 'is_br', 'ticket_type', 'error']), Auth::user(), true)) {
            flash('Help ticket submitted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('helptickets');
    }
}
