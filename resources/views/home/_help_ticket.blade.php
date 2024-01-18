<div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-top">
    <div class="col-6 col-md-4">
    <span class="ubt-texthide">@if(!$helpTicket->is_br)<a href="{{ $helpTicket->url }}">@endif {{ $helpTicket->url }} @if(!$helpTicket->is_br)</a>@endif</span>
    </div>
    <div class="col-6 col-md-3">{!! pretty_date($helpTicket->created_at) !!}</div>
    <div class="col-3 col-md-2">
        @if ($helpTicket->ticket_type == 0)<span class="badge badge-success" style="background-color: #c7161f"> Account </span>
        @endif
        @if ($helpTicket->ticket_type == 1)<span class="badge badge-success" style="background-color: #ac1b72"> Bank </span>
        @endif
        @if ($helpTicket->ticket_type == 2)<span class="badge badge-success" style="background-color: #9c16be"> Character </span>
        @endif
        @if ($helpTicket->ticket_type == 3)<span class="badge badge-success" style="background-color: #aa18cf"> Item </span>
        @endif
        @if ($helpTicket->ticket_type == 4)<span class="badge badge-success" style="background-color: #5f10bf"> MYO </span>
        @endif
        @if ($helpTicket->ticket_type == 5)<span class="badge badge-success" style="background-color: #1031c5"> Player </span>
        @endif
        @if ($helpTicket->ticket_type == 6)<span class="badge badge-success" style="background-color: #079eaf"> Prompt </span>
        @endif
        @if ($helpTicket->ticket_type == 7)<span class="badge badge-success" style="background-color: #0fc862"> Raffle </span>
        @endif
        @if ($helpTicket->ticket_type == 8)<span class="badge badge-success" style="background-color: #59d109"> Shop </span>
        @endif
        @if ($helpTicket->ticket_type == 9)<span class="badge badge-success" style="background-color: #9a8801"> Trait </span>
        @endif
        @if ($helpTicket->ticket_type == 10)<span class="badge badge-success" style="background-color: #de8c00"> Misc </span>
        @endif
      </div>
    <div class="col-6 col-md-1">
        <span class="badge badge-{{ $helpTicket->status == 'Pending' ? 'secondary' : ($helpTicket->status == 'Closed' ? 'success' : 'danger') }}">{{ $helpTicket->status }}</span>
    </div>
    <div class="col-6 col-md-2 text-right">
        @if($helpTicket->status == 'Closed' || ($helpTicket->status == 'Assigned' && $helpTicket->is_br && $helpTicket->error_type != 'exploit') || (Auth::check() && Auth::user()->id == $helpTicket->user_id)) 
            <a href="{{ $helpTicket->viewUrl }}" class="btn btn-primary btn-sm">Details</a>
        @else 
            <a class="btn btn-dark btn-sm text-light">help ticket not closed</a>
        @endif
    </div>
</div>