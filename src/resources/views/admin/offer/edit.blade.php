@extends('admin.layout')
@section('content')

    <main>

        <form id="offer" method="POST" action="{{route('admin.offer.edit', $item)}}">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{route('admin.offer.index')}}" class="btn btn-secondary">{{__('admin.crud.back')}}</a>
                            <button type="submit" form="offer" class="btn btn-primary">{{__('admin.crud.'.($item->id ? 'save' : 'add'))}}</button>
                        </div>
                        <div class="card-body">


                            {!! $form->renderFieldGroup('offer_category_id') !!}
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
