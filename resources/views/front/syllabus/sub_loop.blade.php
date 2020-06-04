<ul>
    @foreach($topics_arr as $id => $syl)
        <li data-id={{$id}}>
            {{ $syl['title'] }}
            <span class="actions">
                @if(array_key_exists($id, $news_arr))
                <span class="link"><a href="">News ({{ $news_arr[$id] }})</a></span>
                @endif
                @if(array_key_exists($id, $ques_arr))
                <span class="link"><a href="">Questions ({{ $ques_arr[$id] }})</a></span>
                @endif
            </span>
            @if(!empty($syl['tp_child']))
                @include('front.syllabus.sub_loop', ['topics_arr' => $syl['tp_child']])
            @endif
        </li>
    @endforeach
</ul>
