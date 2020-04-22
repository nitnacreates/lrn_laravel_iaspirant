<ol>
    @foreach($syllabus_arr as $curr_arr)
        <li hl-attr="{{ $curr_arr['id'] }}">
            {{ $curr_arr['title'] }}
            <span class="d-inline-block">
                <a class="btn btn-primary btn-circle btn-sm" href="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub, 'id' => $curr_arr['id'], 'mode' => 'add']) }}" title="Add Child"><i class="fa fa-plus"></i></a>
                <a class="btn btn-warning btn-circle btn-sm" href="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub, 'id' => $curr_arr['id'], 'mode' => 'display']) }}" title="Edit Name"><i class="fa fa-edit "></i></a>
            </span>
            @if(isset($curr_arr['children']))
                @include('super.partials.syllabus', array('syllabus_arr' => $curr_arr['children']))
            @endif
        </li>
    @endforeach
</ol>
