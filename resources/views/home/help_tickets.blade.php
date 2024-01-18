@extends('home.layout')

@section('home-title') helptickets @endsection

@section('home-content')
    {!! breadcrumbs(['helpTickets' => 'helptickets']) !!}
<h1>
My Help Tickets
</h1>

<div class="text-right">
        <a href="{{ url('helptickets/new') }}" class="btn btn-success">New Help Ticket</a>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !Request::get('type') || Request::get('type') == 'pending' ? 'active' : '' }}" href="{{ url('helptickets') }}">Pending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::get('type') == 'approved'  }}" href="{{ url('helptickets') . '?type=assigned' }}">Assigned</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::get('type') == 'closed'  }}" href="{{ url('helptickets') . '?type=closed' }}">Closed</a>
    </li>
</ul>

@if(count($helpTickets))
    {!! $helpTickets->render() !!}
    <div class="row ml-md-2">
      <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-bottom">
        <div class="col-6 col-md-4 font-weight-bold">Link/Title</div>
        <div class="col-6 col-md-3 font-weight-bold">Submitted</div>
        <div class="col-6 col-md-2 font-weight-bold">Type</div>
        <div class="col-12 col-md-1 font-weight-bold">Status</div>
      </div>
            @foreach($helpTickets as $helpTicket)
                @include('home._help_ticket', ['helpTicket' => $helpTicket])
            @endforeach
      </div>
    {!! $helpTickets->render() !!}
    <div class="text-center mt-4 small text-muted">{{ $helpTickets->total() }} result{{ $helpTickets->total() == 1 ? '' : 's' }} found.</div>
@else 
    <p>No help tickets found.</p>
@endif

@endsection
