<section x-data="{
    password: '',
    showCurrent: false,
    showNew: false,
    showConfirm: false,
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
        return ['', 'Yếu', 'Trung bình', 'Khá', 'Mạnh'][this.strength()] ?? '';
    },
    strengthColor() {
        return ['', 'bg-red-400', 'bg-yellow-400', 'bg-emerald-400', 'bg-emerald-600'][this.strength()] ?? '';
    }
}">
    <header>
        <h2 class="card-title">Cập nhật mật khẩu</h2>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Dùng mật khẩu đủ dài và phức tạp để bảo vệ tài khoản.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Mật khẩu hiện tại" />
            <div class="relative">
                <x-text-input id="update_password_current_password" name="current_password"
                    class="mt-1 block w-full pr-10" autocomplete="current-password"
                    x-bind:type="showCurrent ? 'text' : 'password'" />
                <button type="button" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="showCurrent = !showCurrent">
                    <svg x-show="!showCurrent" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showCurrent" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Mật khẩu mới" />
            <div class="relative">
                <x-text-input id="update_password_password" name="password"
                    class="mt-1 block w-full pr-10" autocomplete="new-password"
                    x-bind:type="showNew ? 'text' : 'password'"
                    x-model="password" />
                <button type="button" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="showNew = !showNew">
                    <svg x-show="!showNew" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showNew" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
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
            <x-input-label for="update_password_password_confirmation" value="Xác nhận mật khẩu mới" />
            <div class="relative">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                    class="mt-1 block w-full pr-10" autocomplete="new-password"
                    x-bind:type="showConfirm ? 'text' : 'password'" />
                <button type="button" tabindex="-1" aria-label="Hiển thị/ẩn mật khẩu"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600"
                    x-on:click="showConfirm = !showConfirm">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Đổi mật khẩu</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="flex items-center gap-1 text-sm font-semibold text-emerald-800"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mật khẩu đã được cập nhật.
                </p>
            @endif
        </div>
    </form>
</section>
