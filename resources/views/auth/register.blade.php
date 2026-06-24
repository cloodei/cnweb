<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-3xl font-semibold text-stone-950">Tạo tài khoản</h1>
        <p class="mt-2 text-sm leading-6 text-stone-600">Tài khoản dùng để đóng góp địa điểm và quản lý lịch trình của riêng bạn.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5"
        x-data="{
            password: '',
            strength() {
                if (this.password.length === 0) return 0;
                let s = 0;
                if (this.password.length >= 8) s++;
                if (/[A-Z]/.test(this.password)) s++;
                if (/[0-9]/.test(this.password)) s++;
                if (/[^A-Za-z0-9]/.test(this.password)) s++;
                return s;
            },
            strengthLabel() {
                const labels = ['', 'Yếu', 'Trung bình', 'Khá', 'Mạnh'];
                return labels[this.strength()] ?? '';
            },
            strengthColor() {
                const colors = ['', 'bg-red-400', 'bg-yellow-400', 'bg-emerald-400', 'bg-emerald-600'];
                return colors[this.strength()] ?? '';
            },
            showPass: false,
            showConfirm: false
        }">
        @csrf

        <div>
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" placeholder="Nguyễn Văn A" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mật khẩu" />
            <div class="relative">
                <x-text-input id="password" class="block w-full pr-10" name="password"
                    required autocomplete="new-password" placeholder="Tối thiểu 8 ký tự"
                    x-bind:type="showPass ? 'text' : 'password'"
                    x-model="password" />
                <button type="button"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="showPass = !showPass" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu">
                    <x-icon name="eye" x-show="!showPass" class="h-5 w-5" />
                    <x-icon name="eye-off" x-show="showPass" class="h-5 w-5" />
                </button>
            </div>
            {{-- Password strength indicator --}}
            <div x-show="password.length > 0" class="mt-2 space-y-1">
                <div class="flex gap-1">
                    <template x-for="i in 4">
                        <div class="h-1 flex-1 rounded-full transition-colors duration-300"
                            :class="i <= strength() ? strengthColor() : 'bg-stone-200'"></div>
                    </template>
                </div>
                <p class="text-xs text-stone-500">Độ mạnh: <span class="font-medium" x-text="strengthLabel()"></span></p>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Xác nhận mật khẩu" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full pr-10" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Nhập lại mật khẩu"
                    x-bind:type="showConfirm ? 'text' : 'password'" />
                <button type="button"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="showConfirm = !showConfirm" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu">
                    <x-icon name="eye" x-show="!showConfirm" class="h-5 w-5" />
                    <x-icon name="eye-off" x-show="showConfirm" class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="action-primary w-full">Tạo tài khoản</button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-600">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="link-quiet font-semibold">Đăng nhập</a>
    </p>
</x-guest-layout>
