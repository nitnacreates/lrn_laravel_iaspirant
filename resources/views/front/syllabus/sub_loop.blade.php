<ul>
    @foreach($topics_arr as $id => $syl)
        <li>
            {{ $syl['title'] }}
            @if(!empty($syl['tp_child']))
                @include('front.syllabus.sub_loop', ['topics_arr' => $syl['tp_child']])
            @endif
        </li>
    @endforeach
</ul>
