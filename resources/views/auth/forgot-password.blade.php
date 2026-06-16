<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Quên mật khẩu</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Nhập email tài khoản, hệ thống sẽ gửi link đặt lại mật khẩu.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('login') }}" class="link-quiet text-sm">Quay lại đăng nhập</a>
            <x-primary-button>Gửi link đặt lại</x-primary-button>
        </div>
    </form>
</x-guest-layout>
