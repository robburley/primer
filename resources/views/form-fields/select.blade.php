<div class="c-field">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>

    {!! Form::select($customField->slug, $customField->formatOptions(json_decode($customField->options)), $value ?? $customField->default, ['class' => 'c-select', 'placeholder' => $customField->placeholder]) !!}

    {!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}
</div>