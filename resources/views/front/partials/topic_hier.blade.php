<span class="bread_topic d-inline-block">
<?php $counter = sizeof($topic_arr[$topic_id]); ?>
@foreach($topic_arr[$topic_id] as $curr_id => $curr_topic)
    <a href="{{ route('front::syllabus-news', ['param' => $sub, 'topic' => $curr_id]) }}"><span class="topic_crumb">{{ $curr_topic }}</span></a>
    @if(--$counter) <i class="fa fa-angle-double-right" aria-hidden="true"></i> @endif
@endforeach
</span>
