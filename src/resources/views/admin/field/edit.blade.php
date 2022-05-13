<div class="card">
    <div class="card-header">{{__('admin.field.plural')}}</div>
    <div class="card-body">
        @foreach($fields as $field)

            <div class="form-group">
                <label for="field_{{$field['name']}}">{{$field['name']}}</label>

                @if($field->type == 'head')
                    <input id="field_{{$field['name']}}"
                           name="field[{{$field['name']}}]"
                           type="text"
                           value="{{$field['value']}}"
                           class="form-control"
                    >
                @elseif($field->type == 'text')
                    <textarea id="field_{{$field['name']}}"
                           name="field[{{$field['name']}}]"
                           type="text"
                           class="form-control ckeditorStandard"
                    >{{$field['value']}}</textarea>
                @else
                    <p>
                        ERROR - make input for '{{$field->type}} type!
                    </p>
                @endif
            </div>

        @endforeach
    </div>
</div>
