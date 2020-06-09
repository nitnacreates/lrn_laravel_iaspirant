<span class="weightage_star d-inline-block">
    @for($i=1; $i<=$total; $i++)
        <i class="fa fa-star @if($i<=$weight) text-warning @endif" aria-hidden="true"></i> 
    @endfor
</span>
