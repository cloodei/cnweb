<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Quên mật khẩu</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Nhập email tài khoản, hệ thống sẽ gửi link đặt lại mật khẩu đến hòm thư của bạn.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')"
                required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('login') }}" class="link-quiet text-sm">← Quay lại đăng nhập</a>
            <button type="submit" class="action-primary">Gửi link đặt lại</button>
        </div>
    </form>
</x-guest-layout>
