<?php $counter = 1; ?>
@foreach ($news as $current_news)
    <tr>
        <td>{{ $counter++ }}</td>
        <td>{{ config('aspirant.subject_map')[$current_news->sub] }}</td>
        <td>{{ $current_news->syllabi_id}}</td>
        <td>@switch($current_news->category)
            @case('b') Both @break
            @case('p') Prelims @break
            @case('m') Mains @break
        @endswitch</td>
        <td>{{ $current_news->weightage}}</td>
        <td>{{ $current_news->created_on}}</td>
        <td>{{ $current_news->visible_from}}</td>
        <td>{{ $current_news->locked ? 'Yes' : 'No' }}</td>
        <td><a href="{{ route('super::news', ['action' => 'stash', 'id' => $current_news->id, 'mode' => 'display'])  }}">{{ $current_news->news_content->news_title}}</a></td>
        <td>{{ $current_news->news_content->small_news_content}}</td>
    </tr>
@endforeach
