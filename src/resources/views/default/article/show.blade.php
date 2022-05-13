@extends('default.layout')
@section('content')

    <h1>Article module</h1>

    <img src="{{renderImage($item->galleryCover(), 400, 200, 'resize')}}" alt="{{$galleryItem->name ?? ''}}">

    {{$item}}


    @foreach($item->gallery->activeItemsWithoutCover as $galleryItem)
        <img src="{{renderImage($galleryItem->url, 400, 200, 'resize')}}" alt="{{$galleryItem->name ?? ''}}">
    @endforeach

@endsection
