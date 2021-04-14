@extends('admin.layout')

@section('admin-title') Figure Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Figure Categories' => 'admin/world/figure-categories']) !!}

<h1>Figure Categories</h1>

<div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/world/figure-categories/create') }}"><i class="fas fa-plus"></i> Create New Figure Category</a></div>
@if(!count($categories))
    <p>No figure categories found.</p>
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
                        <a href="{{ url('admin/world/figure-categories/edit/'.$category->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/world/figure-categories/sort']) !!}
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
        e.prfigureDefault();
    });
    $( "#sortable" ).sortable({
        items: '.sort-item',
        handle: ".handle",
        placeholder: "sortable-placeholder",
        stop: function( figure, ui ) {
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