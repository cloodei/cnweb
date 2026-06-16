<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Xác minh email</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Vui lòng mở link xác minh đã gửi đến email của bạn. Nếu chưa nhận được, bạn có thể gửi lại.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success mb-4">
            Link xác minh mới đã được gửi đến email đăng ký.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>Gửi lại email</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-semibold text-stone-600 hover:text-stone-950">
                Đăng xuất
            </button>
        </form>
    </div>
</x-guest-layout>
