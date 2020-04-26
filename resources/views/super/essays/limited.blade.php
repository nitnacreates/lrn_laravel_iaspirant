<?php $counter = 1; ?>
@foreach ($essays as $current_es)
    <tr>
        <td rowspan="2" class="align-middle">{{ $counter++ }}</td>
        <td>{{ config('aspirant.subject_map')[$current_es->sub] }}</td>
        <td>{{ $current_es->syllabi_id}}</td>
        <td>{{ $current_es->word_limit}}</td>
        <td>{{ $current_es->weightage}}</td>
        <td>{{ $current_es->locked ? 'Yes' : 'No'}}</td>
        <td>{{ $current_es->created_on}}</td>
    </tr>
    <tr>
        <td colspan="6"><a href="{{ route('super::essays', ['action' => 'stash', 'id' => $current_es->id])  }}">{{ $current_es->essay_title}}</a></td>
    </tr>
@endforeach
