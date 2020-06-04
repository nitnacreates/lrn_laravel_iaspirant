<ol>
    @foreach($topics_arr as $id => $syl)
        <li hl-attr="{{ $id }}">
            {{ $syl['title'] }}
            <span class="d-inline-block">
                <a class="btn btn-primary btn-circle btn-sm" href="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub, 'id' => $id, 'mode' => 'add']) }}" title="Add Child"><i class="fa fa-plus"></i></a>
                <a class="btn btn-warning btn-circle btn-sm" href="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub, 'id' => $id, 'mode' => 'display']) }}" title="Edit Name"><i class="fa fa-edit "></i></a>
            </span>
            @if(!empty($syl['tp_child']))
                @include('super.partials.syllabus', array('topics_arr' => $syl['tp_child']))
            @endif
        </li>
    @endforeach
</ol>
