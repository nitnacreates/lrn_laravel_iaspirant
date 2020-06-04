<div class="container">
    <div class="row">
        <div class="col">
            <ol>
                @foreach($topics_arr as $id => $syl)
                    <li>
                        <span class="label">{{ $syl['title'] }}</span>
                        <input type="radio" name="syllabus-pick" value="{{ $id }}">
                        @if(!empty($syl['tp_child']))
                            @include('front.partials.syllabus_picker', array('topics_arr' => $syl['tp_child']))
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
