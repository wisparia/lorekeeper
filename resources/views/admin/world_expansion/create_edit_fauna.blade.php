@extends('admin.layout')

@section('admin-title') Fauna @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Fauna' => 'admin/world/faunas', ($fauna->id ? 'Edit' : 'Create').' Fauna' => $fauna->id ? 'admin/world/faunas/edit/'.$fauna->id : 'admin/world/faunas/create']) !!}

<h1>{{ $fauna->id ? 'Edit' : 'Create' }} Fauna
    @if($fauna->id)
        ({!! $fauna->displayName !!})
        <a href="#" class="btn btn-danger float-right delete-fauna-button">Delete Fauna</a>
    @endif
</h1>

{!! Form::open(['url' => $fauna->id ? 'admin/world/faunas/edit/'.$fauna->id : 'admin/world/faunas/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="row mx-0 px-0">
    <div class="form-group col-md px-0 pr-md-1">
        {!! Form::label('Name') !!}
        {!! Form::text('name', $fauna->name, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md px-0 pr-md-1">
        {!! Form::label('Scientific Name (Optional)') !!}
        {!! Form::text('scientific_name', $fauna->scientific_name, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('Category') !!} {!! add_help('What category of fauna is this?') !!}
    {!! Form::select('category_id', [0=>'Choose a Fauna Category'] + $categories, $fauna->category_id, ['class' => 'form-control selectize', 'id' => 'category']) !!}
</div>

<div class="form-group">
    {!! Form::label('Summary (Optional)') !!}
    {!! Form::text('summary', $fauna->summary, ['class' => 'form-control']) !!}
</div>

<h3>Images</h3>
<div class="form-group">
    @if($fauna->thumb_extension)
        <a href="{{$fauna->thumbUrl}}"  data-lightbox="entry" data-title="{{ $fauna->name }}"><img src="{{$fauna->thumbUrl}}" class="mw-100 float-left mr-3" style="max-height:125px"></a>
    @endif
    {!! Form::label('Thumbnail Image (Optional)') !!} {!! add_help('This thumbnail is used on the fauna index.') !!}
    <div>{!! Form::file('image_th') !!}</div>
    <div class="text-muted">Recommended size: 200x200</div>
    @if(isset($fauna->thumb_extension))
        <div class="form-check">
            {!! Form::checkbox('remove_image_th', 1, false, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-off' => 'Leave Thumbnail As-Is', 'data-on' => 'Remove Thumbnail Image']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    @if($fauna->image_extension)
        <a href="{{$fauna->imageUrl}}"  data-lightbox="entry" data-title="{{ $fauna->name }}"><img src="{{$fauna->imageUrl}}" class="mw-100 float-left mr-3" style="max-height:125px"></a>
    @endif
    {!! Form::label('Fauna Image (Optional)') !!} {!! add_help('This image is used on the fauna page as a header.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: None (Choose a standard size for all fauna header images.)</div>
    @if(isset($fauna->image_extension))
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-off' => 'Leave Header Image As-Is', 'data-on' => 'Remove Current Header Image']) !!}
        </div>
    @endif
</div>

<h3>Description</h3>
<div class="form-group" style="clear:both">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $fauna->description, ['class' => 'form-control wysiwyg']) !!}
</div>

@if($fauna->id)
    <h3>Associated Items</h3>
    <div class="form-group row">
        <div id="itemList" class="col-12 row">
            @foreach($fauna->items as $item)
                <div class="d-flex mb-2 col-4">
                    {!! Form::select('item_id['.$item->id.']', $items, $item->id, ['class' => 'form-control mr-2 item-select original', 'placeholder' => 'Select Item']) !!}
                    <a href="#" class="remove-item btn btn-danger mb-2">×</a>
                </div>
            @endforeach
        </div>
        <div class="col-12 text-right"><a href="#" class="btn btn-primary" id="add-item">Add Item</a></div>
    </div>

    <h3>Associated Locations</h3>
    <div class="form-group row">
        <div id="locationList" class="col-12 row">
            @foreach($fauna->locations as $location)
                <div class="d-flex mb-2 col-4">
                    {!! Form::select('location_id['.$location->id.']', $locations, $location->id, ['class' => 'form-control mr-2 location-select original', 'placeholder' => 'Select Location']) !!}
                    <a href="#" class="remove-location btn btn-danger mb-2">×</a>
                </div>
            @endforeach
        </div>
        <div class="col-12 text-right"><a href="#" class="btn btn-primary" id="add-location">Add Location</a></div>
    </div>
@endif


<div class="form-group">
    {!! Form::checkbox('is_active', 1, $fauna->id ? $fauna->is_active : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the category will not be visible to regular users.') !!}
</div>

<div class="text-right">
    {!! Form::submit($fauna->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}


<div class="item-row hide mb-2 col-4">
    {!! Form::select('item_id[]', $items, null, ['class' => 'form-control mr-2 item-select', 'placeholder' => 'Select Item']) !!}
    <a href="#" class="remove-item btn btn-danger mb-2">×</a>
</div>

<div class="location-row hide mb-2 col-4">
    {!! Form::select('location_id[]', $locations, null, ['class' => 'form-control mr-2 location-select', 'placeholder' => 'Select Location']) !!}
    <a href="#" class="remove-location btn btn-danger mb-2">×</a>
</div>

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-fauna-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/world/faunas/delete') }}/{{ $fauna->id }}", 'Delete Fauna');
    });
    $('.selectize').selectize();

    $('.original.item-select').selectize();
    $('#add-item').on('click', function(e) {
        e.preventDefault();
        addItemRow();
    });
    $('.remove-item').on('click', function(e) {
        e.preventDefault();
        removeItemRow($(this));
    })
    function addItemRow() {
        var $clone = $('.item-row').clone();
        $('#itemList').append($clone);
        $clone.removeClass('hide item-row');
        $clone.addClass('d-flex');
        $clone.find('.remove-item').on('click', function(e) {
            e.preventDefault();
            removeItemRow($(this));
        })
        $clone.find('.item-select').selectize();
    }
    function removeItemRow($trigger) {
        $trigger.parent().remove();
    }

    $('.original.location-select').selectize();
    $('#add-location').on('click', function(e) {
        e.preventDefault();
        addLocationRow();
    });
    $('.remove-location').on('click', function(e) {
        e.preventDefault();
        removeItemRow($(this));
    })
    function addLocationRow() {
        var $clone = $('.location-row').clone();
        $('#locationList').append($clone);
        $clone.removeClass('hide location-row');
        $clone.addClass('d-flex');
        $clone.find('.remove-location').on('click', function(e) {
            e.preventDefault();
            removeLocationRow($(this));
        })
        $clone.find('.location-select').selectize();
    }
    function removeLocationRow($trigger) {
        $trigger.parent().remove();
    }
});
    
</script>
@endsection
