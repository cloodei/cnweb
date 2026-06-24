<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Đăng nhập</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">Tiếp tục quản lý kho địa điểm, nhóm và lịch trình chung.</p>
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
                <button
                    type="button"
                    class="absolute right-0 inset-y-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="show = !show"
                    tabindex="-1"
                    aria-label="Hiển thị/ẩn mật khẩu"
                >
                    <x-icon name="eye" x-show="!show" class="h-5 w-5" />
                    <x-icon name="eye-off" x-show="show" class="h-5 w-5" />
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
