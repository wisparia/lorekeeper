<?php namespace App\Services;

use App\Services\Service;

use Carbon\Carbon;

use DB;
use Config;
use Image;
use Notifications;
use Settings;

use App\Models\User\User;
use App\Models\Character\Character;
use App\Models\HelpTicket\HelpTicket;
use App\Models\Currency\Currency;
use App\Models\Item\Item;
use App\Models\Loot\LootTable;
use App\Models\Prompt\Prompt;

class HelpTicketManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | helpticket Manager
    |--------------------------------------------------------------------------
    |
    | Handles creation and modification of helpticket data.
    |
    */

    /**
     * Creates a new helpticket.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @param  bool                   $isClaim
     * @return mixed
     */
    public function createHelpTicket($data, $user, $isClaim = false)
    {
        DB::beginTransaction();

        try {
            // 1. check that the prompt can be submitted at this time
            // 2. check that the characters selected exist (are visible too)
            // 3. check that the currencies selected can be attached to characters
            if(!Settings::get('is_helptickets_open')) throw new \Exception("The prompt queue is closed for helptickets.");
            if(!isset($data['is_br'])) $data['is_br'] = 0;
            if(!isset($data['ticket_type'])) $data['ticket_type'] = 10;
            
            //dd($data['error']);

            $helpTicket = HelpTicket::create([
                'user_id' => $user->id,
                'url' => $data['url'],
                'status' => 'Pending',
                'comments' => $data['comments'],
                'error_type' => $data['error'],
                'is_br' => $data['is_br'],
                'ticket_type' => $data['ticket_type'],
                ]);
            
            return $this->commitReturn($helpTicket);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Approves a helpticket.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function assignHelpTicket($data, $user)
    {
        DB::beginTransaction();

        try {

            $helpTicket = HelpTicket::where('staff_id', NULL)->where('id', $data['id'])->first();
            if(!$helpTicket) throw new \Exception("This has been assigned an admin");

            $helpTicket->update([
                'staff_id' => $user->id,
                'status' => 'Assigned',
            ]);

            Notifications::create('HELP_TICKET_ASSIGNED', $helpTicket->user, [
                'staff_url' => $user->url,
                'staff_name' => $user->name,
                'helpticket_id' => $helpTicket->id,
            ]);

            return $this->commitReturn($helpTicket);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Closes a helpticket.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function closeHelpTicket($data, $user)
    {
        DB::beginTransaction();

        try {
            if(!isset($data['helpTicket'])) $helpTicket = HelpTicket::where('status', 'Assigned')->where('id', $data['id'])->first();
            elseif($data['helpTicket']->status == 'Assigned') $helpTicket = $data['helpTicket'];
            else $helpTicket = null;
            if(!$helpTicket) throw new \Exception("Invalid helpticket.");
			
			if(isset($data['staff_comments']) && $data['staff_comments']) $data['parsed_staff_comments'] = parse($data['staff_comments']);
			else $data['parsed_staff_comments'] = null;

            $helpTicket->update([
                'staff_comments' => $data['staff_comments'],
				'parsed_staff_comments' => $data['parsed_staff_comments'],
                'staff_id' => $user->id,
                'status' => 'Closed'
            ]);

            Notifications::create('HELP_TICKET_CLOSED', $helpTicket->user, [
                'staff_url' => $user->url,
                'staff_name' => $user->name,
                'helpticket_id' => $helpTicket->id,
            ]);

            return $this->commitReturn($helpTicket);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
    
}