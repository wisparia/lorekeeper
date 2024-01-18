@extends('layouts.app')

@section('title') Help Tickets @endsection

@section('content')
    {!! breadcrumbs(['helptickets' => 'helptickets']) !!}
<h1>
Help Tickets
</h1>

@if(Auth::check())
    <div class="text-right">
            <a href="{{ url('helptickets/new') }}" class="btn btn-success">New Help Ticket</a>
    </div>
@endif
<br>
{!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-3 mb-3">
            {!! Form::text('url', Request::get('url'), ['class' => 'form-control', 'placeholder' => 'URL / Title']) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}

@if(count($helpTickets))
{!! $helpTickets->render() !!}
    <div class="row ml-md-2">
      <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-bottom">
        <div class="col-6 col-md-4 font-weight-bold">Link/Title</div>
        <div class="col-6 col-md-3 font-weight-bold">Submitted</div>
        <div class="col-6 col-md-2 font-weight-bold">Type</div>
        <div class="col-12 col-md-1 font-weight-bold">Status</div>
      </div>
            @foreach($helpTickets as $helpticket)
                @include('home._helpticket', ['helpticket' => $helpticket])
            @endforeach
      </div>
    {!! $helpTickets->render() !!}
    <div class="text-center mt-4 small text-muted">{{ $helpTickets->total() }} result{{ $helpTickets->total() == 1 ? '' : 's' }} found.</div>
@else 
    <p>No open tickets found.</p>
@endif

@endsection
