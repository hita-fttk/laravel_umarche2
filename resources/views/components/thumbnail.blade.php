@php
if($type === 'shops' )
{
    $path = 'storage/shops/';
}
if($type === 'products' )
{
    $path = 'storage/product/';
}

@endphp

<div>
    @if(empty($filename))
    <img src="{{asset('storage/images/no_image.jpg')}}">
    @else
    <img src="{{ asset($path.$filename) }}">
    @endif
</div>