<section class="space-y-6">
    <header>
        <h2 class="card-title text-red-700">Xóa tài khoản</h2>
        <p class="mt-2 text-sm leading-6 text-stone-600">
            Hành động này không thể hoàn tác. Các dữ liệu sau đây sẽ bị xóa vĩnh viễn:
        </p>
        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-stone-600">
            <li>Tài khoản và thông tin cá nhân của bạn.</li>
            <li>Tất cả lịch trình bạn đã tạo.</li>
        </ul>
        <p class="mt-2 text-sm text-stone-500">
            Các địa điểm bạn đã đóng góp vào kho chung sẽ không bị xóa.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Xóa tài khoản</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Bạn có chắc muốn xóa tài khoản?</h2>
                    <p class="mt-1 text-sm text-stone-600">
                        Toàn bộ lịch trình của bạn sẽ bị xóa vĩnh viễn. Hành động này không thể hoàn tác.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <x-input-label for="confirm_delete_password" value="Nhập mật khẩu để xác nhận" />

                <x-text-input
                    id="confirm_delete_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Mật khẩu hiện tại"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Hủy bỏ
                </x-secondary-button>

                <x-danger-button>
                    Xác nhận xóa tài khoản
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
