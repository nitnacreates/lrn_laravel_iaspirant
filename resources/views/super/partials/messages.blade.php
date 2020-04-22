@if(!is_null($errors) && (count($errors) > 0))
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-exclamation-triangle"></i>
                            Alerts
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fa fa-info"></i> Messages!</h5>
                            {{ $error }}
                        </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
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
                <div class="card card-default">
                    <div class="card-body">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fa fa-info"></i> Messages!</h5>
                            @foreach ($msgs as $msg)
                            {{ $msg }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    @endif
@endif
