<button {{ $attributes->merge(['type' => 'button', 'class' => 'action-secondary']) }}>
    {{ $slot }}
</button>
