@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-user"></i> Users</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10px">Id</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Email Verified</th>
                        <th>Subscription</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody id="user_display">
                    @foreach($users as $user)
                    <tr>
                        <td><a href="{{ route('super::users', ['action' => 'stash', 'id' => $user->id]) }}">{{ $user->id }}</a></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ empty($user->email_verified_at) ? 'NO' : 'YES' }}</td>
                        <td>{{ config('aspirant.user_subscription')[$user->subscription] }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            {{ $users->links() }}
        </div>
    </div>
</section>
@stop
