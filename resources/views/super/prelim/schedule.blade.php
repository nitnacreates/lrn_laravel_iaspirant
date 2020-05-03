@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-question-circle-o"></i> Roster</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 5%">Test</th>
                        <th style="width: 10%">Title</th>
                        <th style="width: 30%">Syllabus</th>
                        <th style="width: 30%">Resource</th>
                        <th style="width: 4%">Weightage</th>
                        <th style="width: 4%">Locked</th>
                        <th style="width: 5%">Begins</th>
                        <th style="width: 12%">Action</th>
                    </tr>
                </thead>
                <tbody id="news_display">
                    @foreach ($rosters as $roster)
                    <tr>
                        <td>{{ $roster->id }}</td>
                        <td>{{ $roster->title }}</td>
                        <td>{{ $roster->syllabus }}</td>
                        <td>{{ $roster->resource }}</td>
                        <td>{{ $roster->weightage }}</td>
                        <td>{{ $roster->locked ? "Yes" : "No"}}</td>
                        <td>{{ $roster->available_from }}</td>
                        <td><a href="{{ route('super::question', ['action' => 'pariksha', 'id' => $roster->id]) }}">Questions</a> | <a href="{{ route('super::question', ['action' => 'pariksha-uttar', 'id' => $roster->id]) }}">Attmpts</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@stop
