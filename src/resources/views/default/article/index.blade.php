@extends('default.layout')
@section('content')


    <h1>Articles</h1>

    <div>{!! $fields->text1 !!}</div>

    @foreach($items as $item)
        <a href="{{route('article.show.'.$item->id)}}">
            <h2>{{$item->title}}</h2>
            <p>{!! $item->lead !!}</p>
        </a>
    @endforeach

    {!! $items->links() !!}

@endsection
