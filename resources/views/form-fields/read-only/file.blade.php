<div class="c-field has-addon-right">
    <label class="c-field__label">
        {{ $customField->name }}
    </label>
</div>

<div class="c-field has-addon-right">
    @if($value)
        <a class="c-btn c-btn--info"
           href="{{ $value }}"
        >
            Download
        </a>
    @endif
</div>