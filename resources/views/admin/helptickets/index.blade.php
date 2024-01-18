@extends('admin.layout')

@section('admin-title') helpticket Queue @endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'helpticket Queue' => 'admin/helptickets/pending']) !!}

<h1>
    helpticket Queue
</h1>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link {{ set_active('admin/helptickets/pending*') }} {{ set_active('admin/helptickets') }}" href="{{ url('admin/helptickets/pending') }}">Pending</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ set_active('admin/helptickets/assigned-to-me*') }} {{ set_active('admin/helptickets') }}" href="{{ url('admin/helptickets/assigned-to-me') }}">Assigned To Me</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ set_active('admin/helptickets/assigned') }} {{ set_active('admin/helptickets') }}" href="{{ url('admin/helptickets/assigned') }}">Assigned</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ set_active('admin/helptickets/closed*') }} {{ set_active('admin/helptickets') }}" href="{{ url('admin/helptickets/closed') }}">Closed</a>
  </li>
</ul>

{!! $helpTickets->render() !!}

<div class="row ml-md-2">
  <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-bottom">
    <div class="col-6 col-md-2 font-weight-bold">User</div>
    <div class="col-6 col-md-3 font-weight-bold">Url/Title</div>
    <div class="col-6 col-md-2 font-weight-bold">Submitted</div>
    <div class="col-6 col-md-2 font-weight-bold">Type</div>
    <div class="col-6 col-md-2 font-weight-bold">Status</div>
  </div>

  @foreach($helpTickets as $helpTicket)
  
    <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-top">
      <div class="col-6 col-md-2">{!! $helpTicket->user->displayName !!}</div>
      <div class="col-6 col-md-3">
        <span class="ubt-texthide">@if(!$helpTicket->is_br)<a href="{{ $helpTicket->url }}">@endif {{ $helpTicket->url }} @if(!$helpTicket->is_br)</a>@endif</span>
      </div>
      <div class="col-6 col-md-2">{!! pretty_date($helpTicket->created_at) !!}</div>
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
      <div class="col-3 col-md-2">
        <span class="badge badge-{{ $helpTicket->status == 'Pending' ? 'secondary' : ($helpTicket->status == 'Closed' ? 'success' : 'danger') }}">{{ $helpTicket->status }}</span>{!! $helpTicket->status == 'Assigned' ? ' (to '.$helpTicket->staff->displayName.')' : '' !!}
      </div>
      <div class="col-3 col-md-1"><a href="{{ $helpTicket->adminUrl }}" class="btn btn-primary btn-sm py-0 px-1">Details</a></div>
    </div>
  @endforeach
</div>

{!! $helpTickets->render() !!}
<div class="text-center mt-4 small text-muted">{{ $helpTickets->total() }} result{{ $helpTickets->total() == 1 ? '' : 's' }} found.</div>

@endsection