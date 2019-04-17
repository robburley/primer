<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    {{ Form::hidden($customField->slug . '[]', null) }}
    {!! Form::select($customField->slug . '[]', $customField->formatOptions(json_decode($customField->options)), json_decode($value) ?? $customField->default, ['class' => 'c-select', 'placeholder' => $customField->placeholder, 'multiple']) !!}

    <span class="c-field__addon"><i class="fa fa-tag"></i></span>
</div>

{!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}