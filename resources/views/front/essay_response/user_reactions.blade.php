<div class="row border mt-3">
    <div class="col-6 border-right user-reaction">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Reactions</label>
            <div class="col-sm-8 col-form-label">
                <button data-resp="1" class="px-4 btn btn-sm btn-outline-primary" type="button"><i class="fa fa-thumbs-up" aria-hidden="true" title="like"></i> <span class="total-likes reaction-counter">0</span></button>
                <button data-resp="2" class="px-4 btn btn-sm btn-outline-primary" type="button"><i class="fa fa-thumbs-down" aria-hidden="true" title="dislike"></i> <span class="total-dislikes reaction-counter">0</span></button>
                <button data-resp="3" class="px-4 btn btn-sm btn-outline-danger" type="button"><i class="fa fa-heart" aria-hidden="true" title="save"></i> <span class="total-saved reaction-counter">0</span></button>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Mean Score</label>
            <div class="col-sm-8 col-form-label mean-score">
                7 / 10
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Your Score</label>
            <div class="col-sm-8 your-score">
                <select class="form-control" id="your-score">
                    @for ($i = 1; $i < 11; $i++)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col">
                <label>Comment</label>
                <textarea class="form-control" rows="2"></textarea>
            </div>
            <div class="w-100"></div>
            <div class="col text-right pt-3">
                <button type="button" class="btn btn-primary btn-sm">Comment</button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <legend>Comments</legend>
        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-transparent ">Cras justo odio</li>
            <li class="list-group-item bg-transparent ">Dapibus ac facilisis in</li>
            <li class="list-group-item bg-transparent ">Morbi leo risus</li>
            <li class="list-group-item bg-transparent ">Porta ac consectetur ac</li>
            <li class="list-group-item bg-transparent ">Vestibulum at eros</li>
        </ul>
    </div>
</div>
