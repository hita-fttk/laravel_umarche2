<div>
    @if(empty($filename))
    <img src="{{asset('storage/images/no_image.jpg')}}">
    @else
    <img src="{{ asset('storage/shops/'.$filename) }}">
    @endif
</div>