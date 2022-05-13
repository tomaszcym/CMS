@extends('default.layout')
@section('content')
    <h1>index page</h1>

    {!! $fields->text1 ?? 'brak-' !!}
    {!! $fields->text2 ?? 'brak-' !!}
    {!! $fields->text3 ?? 'brak-' !!}
    {!! $fields->head1 ?? 'brak-' !!}

    @if($gallery)
        @foreach($gallery->items as $galleryItem)
            <div>
                <img src="{{renderImage($galleryItem->url, 400, 200, 'resize')}}" alt="{{$galleryItem->name ?? ''}}">
            </div>
        @endforeach
    @endif

{{--    <hr>--}}
{{--    sdfdsf--}}
{{--    <img src="{{renderImage('asddas')}}" alt="">--}}

{{--    <hr>--}}

    @if(Route::has('article.index'))
        <a href="{{route('article.index')}}">Article index</a>
    @endif


    @include('default.form.contact_form')

@endsection
