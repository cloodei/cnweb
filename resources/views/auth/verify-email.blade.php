<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Xác minh email</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Chúng tôi đã gửi link xác minh đến hòm thư của bạn. Nhấn vào link trong email để kích hoạt tài khoản.
        </p>
        <p class="mt-2 text-sm text-stone-500">
            Kiểm tra cả thư mục <span class="font-medium">Spam / Thư rác</span> nếu không thấy trong hộp thư đến.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm font-medium text-emerald-800">Link xác minh mới đã được gửi đến email của bạn.</p>
            </div>
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        x-data="{ countdown: 0, canResend: true, startCountdown() { this.canResend = false; this.countdown = 60; const t = setInterval(() => { this.countdown--; if (this.countdown <= 0) { clearInterval(t); this.canResend = true; } }, 1000); } }">

        <form method="POST" action="{{ route('verification.send') }}" x-on:submit="startCountdown()">
            @csrf
            <button type="submit" class="action-primary"
                :disabled="!canResend"
                :class="!canResend ? 'opacity-50 cursor-not-allowed' : ''">
                <span x-show="canResend">Gửi lại email</span>
                <span x-show="!canResend">Gửi lại sau (<span x-text="countdown"></span>s)</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-semibold text-stone-600 hover:text-stone-950">
                Đăng xuất
            </button>
        </form>
    </div>
</x-guest-layout>
