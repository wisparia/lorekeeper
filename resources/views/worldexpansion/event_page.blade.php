@extends('worldexpansion.layout')

@section('title') Event :: {{ $event->name }} @endsection

@section('content')
{!! breadcrumbs(['World' => 'world', 'Event' => 'world/events', $event->name => 'world/events/'.$event->id]) !!}
<h1 ><img src="{{$event->thumbUrl}}" style="max-height:25px;vertical-align:inherit;"/>{!! $event->displayName !!}</h1>
<h5>{!! $event->category ? ucfirst($event->category->displayName) : 'Miscellaneous' !!}

@if($event->occur_start || $event->occur_end)
    <span class="ml-4 text-muted">{!! $event->occur_start ? format_date($event->occur_start, false) : '' !!} {!! $event->occur_end ? '- '.format_date($event->occur_end, false) : 'Ongoing' !!}</span>
@endif
</h5>

@if($event->image_extension)
    <div class="text-center"><img src="{{$event->imageUrl}}" class="mw-100 mb-3"/></div>
@endif

@isset($event->summary)
<div class="world-entry-text px-3 text-center">{!! $event->summary !!}</div>
@endisset
    

<div class="row mx-0 px-0 mt-3">
    @if(count($event->locations))
    <div class="text-center col-md mb-3"><div class="card h-100 py-3">
        <h5 class="mb-0">Associated Location{{ count($event->locations) == 1 ? '' : 's' }}</h5>
        <!-- <hr>
        <p class="mb-0">
            @foreach($event->locations as $key => $location)
                @if($location->thumb_extension) 
                    <a href="{{ $location->url }}" data-toggle="tooltip" title="{{ $location->name }}"/><img src="{{$location->thumbUrl}}" class="m-1" style="max-width:100px"/> </a>
                @else  
                    {!! $location->displayName !!}
                @endif
            @endforeach
        </p> -->
        
        <hr>
        @foreach($event->locations->groupBy('type_id') as $key => $locations)
        <p class="mb-0">
            <strong>
                @if($location_types->find($key))
                    {!! count($location_types->find($key)->locations) == 1 ? $location_types->find($key)->name : $location_types->find($key)->names !!}:
                @endif
            </strong> 
            @foreach($locations as $key => $location) <strong>{!! $location->displayName !!}</strong><span>@if($key != count($locations)-1 && count($locations)>2),@endif</span>{{ $key == count($locations)-2 ? ' and ' : '' }}@endforeach
        </p>
        @endforeach
    </div></div>
    @endif

    @if(count($event->figures))
    <div class="text-center col-md mb-3"><div class="card h-100 py-3">
        <h5 class="mb-0">Associated Figure{{ count($event->figures) == 1 ? '' : 's' }}:</h5>
        <!-- <hr>
        <p class="mb-0">
            @foreach($event->figures as $key => $figure)
                @if($figure->has_image) 
                    <a href="{{ $figure->url }}" data-toggle="tooltip" title="{{ $figure->name }}"/><img src="{{$figure->imageUrl}}" class="m-1" style="max-width:100px"/> </a>
                @else  
                    {!! $figure->displayName !!}
                @endif
            @endforeach
        </p> -->
        
        <hr>

        @foreach($event->figures->groupBy('category_id') as $key => $figures)
        <p class="mb-0">
            <strong>
                {{ $figure_categories->find($key) ? $figure_categories->find($key)->name : 'Miscellanous' }}:
            </strong> 
            @foreach($figures as $key => $figure) <strong>{!! $figure->displayName !!}</strong>@if($key != count($figures)-1 && count($figures)>2),@endif @if($key == count($figures)-2) and @endif @endforeach
        </p>
        @endforeach
        
    </div></div>
    @endif
</div>

@isset($event->parsed_description)
<div class="world-entry-text px-3">
    {!! $event->parsed_description !!}
</div>
@endisset



@endsection
