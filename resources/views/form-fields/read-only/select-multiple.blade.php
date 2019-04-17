<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    @if($value)
        {{ collect(json_decode($customField->options))
            ->only(
                collect(json_decode($value))->filter(function($item){ return $item != ''; })
             )
             ->implode(', ')
        }}
    @else
        {{ $customField->default }}
    @endif
</div>