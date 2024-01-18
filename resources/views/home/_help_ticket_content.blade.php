<h1>
   Help Ticket (#{{ $helpTicket->id }})
    <span class="float-right badge badge-{{ $helpTicket->status == 'Pending' ? 'secondary' : ($helpTicket->status == 'Closed' ? 'success' : 'danger') }}">{{ $helpTicket->status }}</span>
</h1>
<div class="mb-1">
    <div class="row">
        <div class="col-md-2 col-4"><h5>User</h5></div>
        <div class="col-md-10 col-8">{!! $helpTicket->user->displayName !!}</div>
    </div>
    <div class="row">
        <div class="col-md-2 col-4"><h5>URL / Title</h5></div>
        <div class="col-md-10 col-8"><a href="{{ $helpTicket->url }}">{{ $helpTicket->url }}</a></div>
    </div>
    @if($helpTicket->is_br == 1)
        <div class="row">
            <div class="col-md-2 col-4"><h5>Bug Type</h5></div>
            <div class="col-md-10 col-8">{{ ucfirst($helpTicket->error_type).($helpTicket->error_type != 'exploit' ? ' Error' : '') }}</div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-2 col-4"><h5>Submitted</h5></div>
        <div class="col-md-10 col-8">{!! format_date($helpTicket->created_at) !!} ({{ $helpTicket->created_at->diffForHumans() }})</div>
    </div>
    @if($helpTicket->status != 'Pending')
        <div class="row">
            <div class="col-md-2 col-4"><h5>Assigned to</h5></div>
            <div class="col-md-10 col-8">{!! $helpTicket->staff->displayName !!} at {!! format_date($helpTicket->updated_at) !!} ({{ $helpTicket->updated_at->diffForHumans() }})</div>
        </div>
    @endif
</div>
<h2>Help Ticket Details</h2>
<div class="card mb-3"><div class="card-body">{!! nl2br(htmlentities($helpTicket->comments)) !!}</div></div>

@if(Auth::check() && $helpTicket->status == 'Assigned' && $helpTicket->user == Auth::user() || Auth::user()->hasPower('manage_helptickets'))
<div class="alert alert-danger">Admins will be alerted by new comments, however to keep the conversation organised we ask that you please reply to the admin comment, and only add additional comments when needed. Thank you!</div>
    @comments([ 'type' => 'Staff-User', 'model' => $helpTicket, 'perPage' => 5 ])
@elseif($helpTicket->status == 'Closed')
<div class="alert alert-danger"> You cannot comment on a closed ticket. </div>
@else
<div class="alert alert-danger"> Please await admin assignment. </div>
@endif
@if(Auth::check() && $helpTicket->staff_comments && ($helpTicket->user_id == Auth::user()->id || Auth::user()->hasPower('manage_helptickets')))
    <h2>Staff Comments</h2>
    <div class="card mb-3"><div class="card-body">
	    @if(isset($helpTicket->parsed_staff_comments))
            {!! $helpTicket->parsed_staff_comments !!}
        @else
            {!! $helpTicket->staff_comments !!}
        @endif
		</div></div>
@endif
