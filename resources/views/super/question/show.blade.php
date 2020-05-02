@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
              <div class="row">
              </div>
            </div>
        </div>
        <div class="card-body p-0">

        </div>
        <div class="card-footer text-right">
            <div class="btn-group">
                <a class="btn btn-block btn-primary" href="{{ route('super::question', ['action' => 'stash'])  }}">Add question</a>
            </div>
        </div>
    </div>
</section>
@stop
