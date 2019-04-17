<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    {!! Form::number($customField->slug, $value ?? $customField->default, ['class' => 'c-input', 'placeholder' => $customField->placeholder, 'step' => 0.01]) !!}

    <span class="c-field__addon"><i class="fa fa-hashtag"></i></span>
</div>

{!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}