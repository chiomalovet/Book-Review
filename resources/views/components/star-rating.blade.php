@if ($rating)
    @for ($i = 1; $i <= 5; $i++)
        {{$i <= $rating ? '★' : '☆'}}
    @endfor
@else
    No Rating Yet
@endif