@extends('user.layout')

@section('profile-title') Help Ticket (#{{ $helpTicket->id }}) @endsection

@section('profile-content')
{!! breadcrumbs(['Users' => 'users', $user->name => $user->url, 'helpticket (#' . $helpTicket->id . ')' => $helpTicket->viewUrl]) !!}

@if(Auth::user()->id == $helpTicket->user->id || Auth::user()->hasPower('manage_helptickets') || ($helpTicket->is_br == 1 && ($helpTicket->status == 'Closed' || $helpTicket->error_type != 'exploit')))
    @include('home._help_ticket_content', ['helpTicket' => $helpTicket]) 
@else
    <div class="alert alert-danger">Help tickets are private. Please contact support if you believe this is a mistake.</div>
@endif

@endsection