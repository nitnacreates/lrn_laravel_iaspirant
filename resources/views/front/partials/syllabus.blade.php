<div class="sidebar-item">
  <div class="sidebar-item-radial">
    <div class="side-item-container">
        <h2 class="title">Syllabus</h2>
        <ul class="list-group list-group-flush">
        @foreach(config('aspirant.subject_map') as $key => $val)
            <li class="list-group-item @if(isset($sub) && $sub == $key) active @endif"><a href="{{ route('front::syllabus', ['action' => 'show', 'sub' => $key]) }}">{{$val}}</a></li>
        @endforeach
        </ul>
    </div>
  </div>
</div>
