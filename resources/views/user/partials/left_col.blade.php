<div class="sidebar-item">
  <div class="sidebar-item-radial">
    <div class="side-item-container">
        <h2 class="title">User</h2>
        <ul class="list-group list-group-flush">
            <li class="list-group-item @if(Request::url() == route('user::notes', ['action' => 'create'])) active @endif"><a href="{{ route('user::notes', ['action' => 'create']) }}">Create Note</a></li>
            <li class="list-group-item @if(Request::url() == route('user::media', ['action' => 'show'])) active @endif"><a href="{{ route('user::media', ['action' => 'show']) }}">My Media</a></li>
            <li class="list-group-item"><a href="javascript:void(0);">My Progress</a></li>
            <li class="list-group-item @if(Request::url() == route('user::profile', ['action' => 'show'])) active @endif"><a href="{{ route('user::profile', ['action' => 'show']) }}">My Profile</a></li>
            <li class="list-group-item"><a href="javascript:void(0);">My Subscription</a></li>
        </ul>
    </div>
  </div>
</div>
