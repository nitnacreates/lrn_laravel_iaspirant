<div class="modal-content" id="select-picker-container">
    <div class="modal-header">
        <h4 class="modal-title"><h4><i class="fa fa-book"></i>{{ $sub_title }}</h4></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                @include('front.partials.syllabus_picker', array('topics_arr' => $topics_arr))
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary float-right pick" style="margin-right: 5px;"><i class="fa fa-suitcase"></i> Pick</button>
    </div>
</div>
