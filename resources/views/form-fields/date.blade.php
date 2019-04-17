<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    {!! Form::text($customField->slug, !empty($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : $customField->default, ['class' => 'c-input', 'placeholder' => $customField->placeholder, 'data-toggle' => 'datepicker']) !!}

    <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
</div>

{!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}