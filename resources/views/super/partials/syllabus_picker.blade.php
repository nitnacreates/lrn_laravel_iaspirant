<ol>
    @foreach($topics_arr as $id => $syl)
        <li>
            <span class="label">{{ $syl['title'] }}</span>
            <input type="radio" name="syllabus-pick" value="{{ $id }}">
            @if(!empty($syl['tp_child']))
                @include('super.partials.syllabus_picker', array('topics_arr' => $syl['tp_child']))
            @endif
        </li>
    @endforeach
</ol>
