<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Đăng nhập</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">Tiếp tục quản lý kho địa điểm và lịch trình cá nhân.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <div class="flex items-center justify-between gap-4">
                <x-input-label for="password" value="Mật khẩu" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-emerald-800 hover:text-stone-950"
                        href="{{ route('password.request') }}">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>
            <div class="relative">
                <x-text-input id="password" class="block w-full pr-10" :type="'password'" name="password"
                    required autocomplete="current-password" placeholder="Mật khẩu của bạn"
                    x-bind:type="show ? 'text' : 'password'" />
                <button type="button"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="show = !show" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label class="flex items-center gap-2">
            <input id="remember_me" type="checkbox"
                class="rounded border-stone-300 text-emerald-800 shadow-sm focus:ring-emerald-700" name="remember">
            <span class="text-sm text-stone-600">Ghi nhớ đăng nhập</span>
        </label>

        <button type="submit" class="action-primary w-full">Đăng nhập</button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-600">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" class="link-quiet font-semibold">Tạo tài khoản</a>
    </p>
</x-guest-layout>
