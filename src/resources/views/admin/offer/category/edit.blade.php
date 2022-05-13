@extends('admin.layout')
@section('content')

    <main>

        <form id="offer_category" method="POST" action="{{route('admin.offer_category.edit', $item)}}">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{route('admin.offer_category.index')}}" class="btn btn-secondary">{{__('admin.crud.back')}}</a>
                            <button type="submit" form="offer_category" class="btn btn-primary">{{__('admin.crud.'.($item->id ? 'save' : 'add'))}}</button>
                        </div>
                        <div class="card-body">


                            {!! $form->renderFieldGroup('title') !!}
                            {!! $form->renderFieldGroup('lead') !!}
                            {!! $form->renderFieldGroup('text') !!}

                            <hr>
                            {!! $form->renderFieldGroup('active') !!}


                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('admin.seo.partial', compact('formSeo'))
                </div>
            </div>

        </form>


        @if($item->id)
            @include('admin.gallery.partial', ['gallery' => $item->gallery])
        @endif

    </main>

@endsection
