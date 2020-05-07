@extends('front.layouts.skeleton')
@push('col_left_top')
    @include('user.partials.left_col')
@endpush
@section('content')
<h3>User media</h3>

<div id="media-list">
    <ul></ul>
</div>
<form role="form" method="post" action="{{ route('user::media', ['action' => 'store']) }}" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Current Folder:</label>
                <div class="col-sm-10">
                    <input type="text" name="current-dir" class="form-control" id="media-footer" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Upload Image:</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="create-image" id="imageUploadImage" autocomplete="off" />
                            <label class="custom-file-label" name="create-image" for="imageUploadImage">jpg|png|gif - Will be sanitized</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Create Folder:</label>
                <div class="col-sm-10">
                    <input type="text" name="create-folder" class="form-control" value="" placeholder="Will be sanitized">
                </div>
            </div>
        </div>
    </div>
    <div class="row text-right">
        <div class="col-12">
           <div class="btn-group">
                <button type="submit" class="btn btn-block btn-primary">Submit</button>
            </div>                     
        </div>
    </div>
</form>

<script type="text/javascript">
    var mdjson = {!! json_encode($files_arr) !!}
</script>

<script type="text/unders" id="media-render-folder">
<li class="list-inline-item">
    <a href="javascript:void(0)" class="album" data-path="<%= folderpath %>">
        <i class="fa fa-folder" aria-hidden="true"></i>
        <span class="name d-block"><%= foldername %></span>
    </a>
    <span class="delete">
        <a href="javascript:void(0)" class="delete-current" del-attr="delete-folder" title="delete folder" data-path="<%= folderpath %>"><i class="fa fa-times" aria-hidden="true"></i></a>
    </span>
</li>
</script>
<script type="text/unders" id="media-render-image">
<li class="list-inline-item">
    <a href="<%= imageurl %>" target="_blank">
        <img src="<%= imageurl %>" />
        <span class="name d-block"><%= imagename %></span>
    </a>
    <span class="delete">
        <a href="javascript:void(0)" class="delete-current" del-attr="delete-image" title="delete image" data-path="<%= imageurl %>"><i class="fa fa-times" aria-hidden="true"></i></a>
    </span>
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

@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/js/front_user.js') !!}"></script>
@endpush
