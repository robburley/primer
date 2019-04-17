<div class="c-field">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>

    {{ $value ?? $customField->default }}
</div>