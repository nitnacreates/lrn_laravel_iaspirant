@if(!is_null($errors) && (count($errors) > 0))
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                      {{ $error }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if (Session::has('msgs'))
<?php
    $msgs = Session::get('msgs');
?>
    @if(is_array($msgs) && (count($msgs)))
<section>
    <div class="container-fluid">
        <div class="row">
             <div class="col-12">
                @foreach ($msgs as $msg)
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                      {{ $msg }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
    @endif
@endif
