<section class="space-y-6">
    <header>
        <h2 class="card-title">
            Xóa tài khoản
        </h2>

        <p class="mt-2 text-sm leading-6 text-stone-600">
            Khi tài khoản bị xóa, dữ liệu liên quan sẽ bị xóa vĩnh viễn. Hãy tải hoặc lưu lại thông tin cần thiết trước khi tiếp tục.
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

            <h2 class="card-title">
                Bạn có chắc muốn xóa tài khoản?
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Nhập mật khẩu để xác nhận xóa tài khoản vĩnh viễn.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Mật khẩu" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Mật khẩu"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Hủy
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Xóa tài khoản
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
