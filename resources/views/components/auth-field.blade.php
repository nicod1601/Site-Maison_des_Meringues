@props([
    'name',
    'label',
    'icon'  => 'ti-mail',
    'type'  => 'text',
    'value' => null,
])
@php
    $id         = $attributes->get('id', $name);
    $isPassword = $type === 'password';
    $error      = $errors->first($name);
    $errorId    = $id . '-error';
@endphp

<div class="field {{ $error ? 'has-error' : '' }}">
  <label for="{{ $id }}">
    <i class="ti {{ $icon }}" aria-hidden="true"></i> {{ $label }}
  </label>

  <div class="field-wrap">
    <span class="field-icon" aria-hidden="true"><i class="ti {{ $icon }}"></i></span>

    <input
      id="{{ $id }}"
      type="{{ $type }}"
      name="{{ $name }}"
      @unless($isPassword) value="{{ old($name, $value) }}" @endunless
      @if($error) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
      {{ $attributes->except('id') }}
    />

    @if($isPassword)
      <button type="button" class="toggle-password" data-toggle-password="{{ $id }}"
              aria-label="Afficher le mot de passe" aria-pressed="false">
        <i class="ti ti-eye" aria-hidden="true"></i>
      </button>
    @endif
  </div>

  @if($error)
    <p class="field-error" id="{{ $errorId }}" role="alert">
      <i class="ti ti-alert-circle" aria-hidden="true"></i> {{ $error }}
    </p>
  @endif

  {{ $slot }}
</div>
