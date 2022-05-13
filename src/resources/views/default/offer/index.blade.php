@extends('default.layout')
@section('content')


    <h1>Offers</h1>

    <div>{!! $fields->text1 !!}</div>

    @foreach($items as $item)
        <a href="{{route('offer.show.'.$item->id)}}">
            <h2>{{$item->title}}</h2>
            <p>{!! $item->lead !!}</p>
        </a>
    @endforeach

    {!! $items->links() !!}

@endsection
