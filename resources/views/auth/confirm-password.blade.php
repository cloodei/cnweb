<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Xác nhận mật khẩu</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Đây là vùng bảo mật. Vui lòng nhập lại mật khẩu để tiếp tục.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5"
        x-data="{ show: false }">
        @csrf

        <div>
            <x-input-label for="password" value="Mật khẩu" />
            <div class="relative">
                <x-text-input id="password" class="block w-full pr-10" name="password"
                    required autocomplete="current-password"
                    x-bind:type="show ? 'text' : 'password'" />
                <button type="button"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="show = !show" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu">
                    <x-icon name="eye" x-show="!show" class="h-5 w-5" />
                    <x-icon name="eye-off" x-show="show" class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="action-primary w-full">Xác nhận</button>
    </form>
</x-guest-layout>
