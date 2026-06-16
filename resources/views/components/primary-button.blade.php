<button {{ $attributes->merge(['type' => 'submit', 'class' => 'action-primary']) }}>
    {{ $slot }}
</button>
