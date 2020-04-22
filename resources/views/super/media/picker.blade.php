@extends('super.layouts.stripped')
@section('content') 
<section class="content">
    <div class="card shadow-none">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-picture-o"></i> Media</h3>
        </div>
        <div class="card-body media-picker" id="media-list">
            <ul class="list-inline">
            </ul>
        </div>
    </div>
</section>

<script type="text/javascript">
    var mdjson = {!! json_encode($files_arr) !!}
</script>

<script type="text/unders" id="media-render-folder">
<li class="list-inline-item">
    <a href="javascript:void(0)" class="album" data-path="<%= folderpath %>">
        <i class="fa fa-folder" aria-hidden="true"></i>
        <span class="name d-block"><%= foldername %></span>
    </a>
</li>
</script>
<script type="text/unders" id="media-render-image">
<li class="list-inline-item">
    <a href="javascript:void(0)" class="pick-image" data-href="<%= imageurl %>">
        <img src="<%= imageurl %>" />
        <span class="name d-block"><%= imagename %></span>
    </a>
</li>
</script>
<script type="text/unders" id="media-render-back">
<li class="list-inline-item">
    <a href="javascript:void(0)" class="back">
        <i class="fa fa-backward" aria-hidden="true"></i>
        <span class="name d-block">Go Back</span>
    </a>
</li>
</script> 
@stop
