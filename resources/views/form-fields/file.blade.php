<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">

    {!! Form::file($customField->slug, ['class' => 'c-input']) !!}

    <span class="c-field__addon"><i class="fa fa-file"></i></span>
</div>

{!! $errors->first($customField->slug, '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}

@if($value)
    <a class="c-btn c-btn--info u-mt-small pull-right"
       href="{{ $value }}"
    >
        Download
    </a>
@endif