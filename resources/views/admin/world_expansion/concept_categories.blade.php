@extends('admin.layout')

@section('admin-title') Concept Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Concept Categories' => 'admin/world/concept-categories']) !!}

<h1>Concept Categories</h1>

<div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/world/concept-categories/create') }}"><i class="fas fa-plus"></i> Create New Concept Category</a></div>
@if(!count($categories))
    <p>No concept categories found.</p>
@else
    <table class="table table-sm category-table">
        <tbody id="sortable" class="sortable">
            @foreach($categories as $category)
                <tr class="sort-item" data-id="{{ $category->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        <a href={!! $category->url !!} @if($category->thumb_extension) data-toggle="tooltip" title="<img src='{{$category->thumbUrl}}' style='max-width:100px;'/><br> {{ucfirst($category->name)}} " @endif />{!! $category->name !!}</a>
                        {{ $category->summary ? '('.$category->summary.')' : '' }}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/world/concept-categories/edit/'.$category->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/world/concept-categories/sort']) !!}
        {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
        {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>

$( document ).ready(function() {
    $('.handle').on('click', function(e) {
        e.preventDefault();
    });
    $( "#sortable" ).sortable({
        items: '.sort-item',
        handle: ".handle",
        placeholder: "sortable-placeholder",
        stop: function( event, ui ) {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        },
        create: function() {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        }
    });
    $( "#sortable" ).disableSelection();
});
</script>
@endsection
