<div class="c-field">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>

    {!! Form::textarea($customField->slug, $value ?? $customField->default, ['class' => 'c-input', 'placeholder' => $customField->placeholder]) !!}

    {!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}
</div>