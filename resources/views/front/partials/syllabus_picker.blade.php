<div class="container">
    <div class="row">
        <div class="col">
            <ol>
                @foreach($topics_arr as $curr_arr)
                    <li>
                        <span class="label">{{ $curr_arr['title'] }}</span>
                        <input type="radio" name="syllabus-pick" value="{{ $curr_arr['id'] }}">
                        @if(isset($curr_arr['children']))
                            @include('front.partials.syllabus_picker', array('topics_arr' => $curr_arr['children']))
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
