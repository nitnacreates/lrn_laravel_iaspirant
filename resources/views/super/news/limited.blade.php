@foreach ($news_show->news as $cn)
    <div class="row post">
        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><b class="d-block">Date</b>{{ $news_show->show_on }}</p>
                        <p class="text-sm"><b class="d-block">Locked</b>{{ $news_show->locked ? 'Yes' : 'No' }}</p>
                        <p class="text-sm"><b class="d-block">Created</b>{{ $cn->created_at }}</p>
                        <p class="text-sm"><b class="d-block">Updated</b>{{ $cn->updated_at }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><b class="d-block">Sub</b>{{ config('aspirant.subject_map')[$cn->sub] }}</p>
                        <p class="text-sm"><b class="d-block">Topic</b>{{ $cn->topic_id }}</p>
                        <p class="text-sm"><b class="d-block">Category</b>@switch($cn->category)
                            @case('b') Both @break
                            @case('p') Prelims @break
                            @case('m') Mains @break
                            @endswitch
                        </p>
                        <p class="text-sm"><b class="d-block">Link</b>{{ $cn->news_link }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><b class="d-block">Type</b>{{ config('aspirant.news.type')[$cn->type] }}</p>
                        <p class="text-sm"><b class="d-block">Weightage</b>{{ $cn->weightage }}</p>
                        <p class="text-sm"><b class="d-block">Meta Keys</b>{{ $cn->meta_key }}</p>
                        <p class="text-sm"><b class="d-block">Meta Description</b>{{ $cn->meta_descr }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h3 class="text-primary"><a href="{{ route('super::news', ['action' => 'stash', 'id' => $cn->id]) }}" >{{ $cn->news_title }}</a></h3>
                    <p class="lead">News Content:</p>
                    {!! $cn->news_content !!}
                    <div class="text-muted">
                        <p class="lead">Self Note:</p>
                        {!! $cn->self_note !!}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endforeach
