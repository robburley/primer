<div class="c-field">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>

    <div class="row">
        {{ Form::hidden($customField->slug . '[]', null) }}

        @foreach(json_decode($customField->options) as $key => $option)
            <div class="col-lg-4">
                <div class="c-choice c-choice--checkbox">
                    {!!  Form::checkbox($customField->slug . '[]', $option, $customField->default == $option, ['class' => 'c-choice__input', 'id' => 'checkbox-' . $key . str_slug($option)]) !!}

                    <label class="c-choice__label" for="checkbox-{{ $key . str_slug($option) }}">
                        {{ $option }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    {!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}
</div>