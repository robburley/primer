<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    {{ !empty($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : $customField->default }}
</div>