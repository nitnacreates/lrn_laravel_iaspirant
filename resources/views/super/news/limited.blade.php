<?php $counter = 1; ?>
@foreach ($news as $current_news)
    <tr>
        <td rowspan="2" class="align-middle">{{ $counter++ }}</td>
        <td>{{ config('aspirant.subject_map')[$current_news->sub] }}</td>
        <td>{{ $current_news->topic_id}}</td>
        <td>@switch($current_news->category)
            @case('b') Both @break
            @case('p') Prelims @break
            @case('m') Mains @break
        @endswitch</td>
        <td>{{ config('aspirant.news.type')[$current_news->type]}}</td>
        <td>{{ $current_news->weightage}}</td>
        <td>{{ $current_news->visible_from}}</td>
        <td>{{ $current_news->locked ? 'Yes' : 'No' }}</td>
        <td>{{ $current_news->created_date }}</td>
    </tr>
    <tr>
        <td colspan="8"><a href="{{ route('super::news', ['action' => 'stash', 'id' => $current_news->id])  }}">{{ $current_news->news_title}}</a></td>
    </tr>
@endforeach
