<button {{ $attributes->merge(['type' => 'submit', 'class' => 'action-danger']) }}>
    {{ $slot }}
</button>
