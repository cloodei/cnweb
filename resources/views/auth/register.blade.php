<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Tạo tài khoản</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">Tài khoản dùng để đóng góp địa điểm và quản lý lịch trình của riêng bạn.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nguyễn Văn A" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mật khẩu" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="Tối thiểu 8 ký tự" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Xác nhận mật khẩu" />
            <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="action-primary w-full">Tạo tài khoản</button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-600">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="link-quiet">Đăng nhập</a>
    </p>
</x-guest-layout>
