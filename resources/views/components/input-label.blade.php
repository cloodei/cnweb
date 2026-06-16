@props(['value'])

<label {{ $attributes->merge(['class' => 'label-quiet block']) }}>
    {{ $value ?? $slot }}
</label>
