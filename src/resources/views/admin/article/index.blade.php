@extends('admin.layout')
@section('content')

    <main>

        <div class="card">
            <div class="card-header">
                {{__('admin.article.plural')}}
                <a href="{{route('admin.article.create')}}" class="btn btn-primary">{{__('admin.crud.create')}}</a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-responsive-sm sortable" data-table="article">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td></td>
                            <td>{{__('admin.article.title')}}</td>
                            <td>{{__('admin.article.lead')}}</td>
                            <td>{{__('admin.active')}}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$items->isEmpty())
                        @php($i = 1)
                        @foreach($items as $key=>$article)
                            <tr data-id="{{$article->id}}">
                                <td data-position>{{$i++}}</td>
                                <td style="width: 50px">
                                    <img src="{{renderSmallCover($article)}}" alt="">
                                </td>
                                <td>{{$article->title}}</td>
                                <td><small>{!! $article->lead !!}</small></td>
                                <td>
                                    @if($article->active)
                                        <span class="badge badge-success">{{__('admin.active')}}</span>
                                    @else
                                        <span class="badge badge-warning">{{__('admin.not_active')}}</span>
                                    @endif
                                </td>
                                <td class="text-right text-nowrap">
                                    <a href="{{route('admin.article.show', $article)}}" class="btn btn-info btn-sm"><i data-feather="edit-2" class="mr-2"></i>{{__('admin.crud.edit')}}</a>
                                    <a href="{{route('admin.article.delete', $article)}}" class="btn btn-danger btn-sm"><i data-feather="trash" class="mr-2"></i>{{__('admin.crud.delete')}}</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100">{{__('admin.empty_set')}}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                {{$items->links()}}
            </div>
        </div>

    </main>

@endsection
