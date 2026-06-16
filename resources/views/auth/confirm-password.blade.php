<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Xác nhận mật khẩu</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Khu vực này cần xác nhận lại mật khẩu trước khi tiếp tục.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Mật khẩu" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">Xác nhận</x-primary-button>
    </form>
</x-guest-layout>
