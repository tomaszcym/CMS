@extends('default.layout')
@section('content')

    <h1>Offer module</h1>

    {{$item}}

    @foreach($item->gallery->items as $galleryItem)
        <img src="{{renderImage($galleryItem->url, 400, 200, 'resize')}}" alt="{{$galleryItem->title ?? ''}}">
    @endforeach

@endsection
