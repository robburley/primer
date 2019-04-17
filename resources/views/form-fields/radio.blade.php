<div class="c-field">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>

    <div class="row">
        @foreach(json_decode($customField->options) as $key => $option)
            <div class="col-lg-4">
                <div class="c-choice c-choice--radio">
                    {!!  Form::radio($customField->slug, $option, ($value ?? $customField->default) == $option, ['class' => 'c-choice__input', 'id' =>  'radio-' . $key . $customField->slug]) !!}

                    <label class="c-choice__label" for="radio-{{ $key . $customField->slug }}">
                        {{ $option }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    {!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}
</div>