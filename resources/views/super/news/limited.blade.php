<div class="row border-bottom mb-3">
    <div class="col"><p class="text-sm"><strong>Date:</strong> {{ $news_show->show_on->format('d M, Y') }}</p></div>
    <div class="col"><p class="text-sm"><strong>Total:</strong> {{ $news_show->news->count() }}</p></div>
    <div class="col"><p class="text-sm"><strong>Locked:</strong> {{ $news_show->locked ? 'Yes' : 'No' }}</p></div>
    <div class="col"><button type="button" class="btn btn-sm {{ $news_show->locked ? 'btn-success' : 'btn-danger' }}" id="news_date_action" data-date="{{ $news_show->show_on->format('Y-m-d') }}" value="{{ $news_show->locked ? 0 : 1 }}">{{ $news_show->locked ? 'Unlock' : 'Lock' }}</button></div>
</div>
@foreach ($news_show->news as $cn)
    <div class="row post">
        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><strong>Type:</strong> {{ config('aspirant.news.type')[$cn->type] }}</p>
                        <p class="text-sm"><strong>Created:</strong> {{ $cn->created_at }}</p>
                        <p class="text-sm"><strong>Updated:</strong> {{ $cn->updated_at }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><strong>Sub:</strong> {{ config('aspirant.subject_map')[$cn->sub] }}</p>
                        <p class="text-sm"><strong>Topic:</strong> {{ $cn->topic_id }}</p>
                        <p class="text-sm"><strong>Category:</strong> @switch($cn->category)
                            @case('b') Both @break
                            @case('p') Prelims @break
                            @case('m') Mains @break
                            @endswitch
                        </p>
                        <p class="text-sm"><strong>Link:</strong> {{ $cn->news_link }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-muted">
                        <p class="text-sm"><strong>Weightage:</strong> {{ $cn->weightage }}</p>
                        <p class="text-sm"><strong>Meta Keys:</strong> {{ $cn->meta_key }}</p>
                        <p class="text-sm"><strong>Meta Description:</strong> {{ $cn->meta_descr }}</p>
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
