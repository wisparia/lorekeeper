@extends('admin.layout')

@section('admin-title') Help Ticket (#{{ $helpTicket->id }}) @endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'helpticket Queue' => 'admin/helptickets/pending', 'helpticket (#' . $helpTicket->id . ')' => $helpTicket->viewUrl]) !!}

@if($helpTicket->status !== 'Closed')
    @if($helpTicket->status == 'Assigned' && Auth::user()->id !== $helpTicket->staff_id)
    <div class="alert alert-danger">This help ticket is not assigned to you</div>
    @elseif($helpTicket->status == 'Pending')
    <div class="alert alert-warning">This help ticket needs assigning</div>
    @endif
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
        <div class="row">
            <div class="col-md-2 col-4"><h5>Assigned to</h5></div>
            <div class="col-md-10 col-8">@if($helpTicket->staff != NULL) {!! $helpTicket->staff->displayName !!} @endif</div>
        </div>
    </div>
    <h2>Help Ticket Details</h2>
    <div class="card mb-3"><div class="card-body">{!! nl2br(htmlentities($helpTicket->comments)) !!}</div></div>
    @if(Auth::check() && $helpTicket->staff_comments && ($helpTicket->user_id == Auth::user()->id || Auth::user()->hasPower('manage_helptickets')))
        <h2>Staff Comments ({!! $helpTicket->staff->displayName !!})</h2>
        <div class="card mb-3"><div class="card-body">
		    @if(isset($helpTicket->parsed_staff_comments))
                {!! $helpTicket->parsed_staff_comments !!}
            @else
                {!! $helpTicket->staff_comments !!}
            @endif
		</div></div>
    @endif

    @if($helpTicket->status == 'Assigned' && $helpTicket->user_id == Auth::user()->id || Auth::user()->hasPower('manage_helptickets'))
    @comments([ 'type' => 'Staff-User', 'model' => $helpTicket, 'perPage' => 5 ])
    @endif

    {!! Form::open(['url' => url()->current(), 'id' => 'helpticketForm']) !!}
    @if($helpTicket->status == 'Assigned' && Auth::user()->id == $helpTicket->staff_id)
    @if(Auth::user()->hasPower('manage_helptickets'))<div class="alert alert-warning">Please include a small paragraph on the solution and as many important details as you deem necessary, as the user will no longer be able to view the comments after the help ticket is closed</div>@endif
		<div class="form-group">
            {!! Form::label('staff_comments', 'Staff Comments (Optional)') !!}
			{!! Form::textarea('staff_comments', $helpTicket->staffComments, ['class' => 'form-control wysiwyg']) !!}
        </div>
    @endif
        <div class="text-right">
    @if($helpTicket->staff_id == NULL)
            <a href="#" class="btn btn-danger mr-2" id="assignButton">Assign</a>
    @endif
    @if($helpTicket->status == 'Assigned' && Auth::user()->id == $helpTicket->staff_id)
            <a href="#" class="btn btn-success" id="closalButton">Close</a>
        </div>
    @endif
    {!! Form::close() !!}

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content hide" id="closalContent">
                <div class="modal-header">
                    <span class="modal-title h5 mb-0">Confirm Closal</span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>This will close the help ticket.</p>
                    <div class="text-right">
                        <a href="#" id="closalSubmit" class="btn btn-success">Close</a>
                    </div>
                </div>
            </div>
            <div class="modal-content hide" id="assignContent">
                <div class="modal-header">
                    <span class="modal-title h5 mb-0">Confirm Assignment</span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-left">This will assign yourself to the help ticket.</p>
                    <div class="text-right">
                        <a href="#" id="assignSubmit" class="btn btn-danger">Assign</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">This help ticket has already been closed.</div>
    @include('home._help_ticket_content', ['helpTicket' => $helpTicket])
@endif

@endsection

@section('scripts')
@parent
@if($helpTicket->status !== 'Closed')
    <script>

        $(document).ready(function() {
            var $confirmationModal = $('#confirmationModal');
            var $helpTicketForm = $('#helpticketForm');

            var $closalButton = $('#closalButton');
            var $closalContent = $('#closalContent');
            var $closalSubmit = $('#closalSubmit');

            var $assignButton = $('#assignButton');
            var $assignContent = $('#assignContent');
            var $assignSubmit = $('#assignSubmit');

            $closalButton.on('click', function(e) {
                e.preventDefault();
                $closalContent.removeClass('hide');
                $assignContent.addClass('hide');
                $confirmationModal.modal('show');
            });

            $assignButton.on('click', function(e) {
                e.preventDefault();
                $assignContent.removeClass('hide');
                $closalContent.addClass('hide');
                $confirmationModal.modal('show');
            });

            $closalSubmit.on('click', function(e) {
                e.preventDefault();
                $helpTicketForm.attr('action', '{{ url()->current() }}/close');
                $helpTicketForm.submit();
            });

            $assignSubmit.on('click', function(e) {
                e.preventDefault();
                $helpTicketForm.attr('action', '{{ url()->current() }}/assign');
                $helpTicketForm.submit();
            });
        });

    </script>
@endif
@endsection
