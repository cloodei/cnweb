<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký - Quản lý lịch trình</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased overflow-hidden bg-white">
    <div class="min-h-screen flex">
        
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Bắt đầu hành trình! 🚀</h2>
                    <p class="text-gray-500 text-sm">Tạo tài khoản để tự do thiết kế chuyến đi của riêng bạn.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                        <input id="name" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition-colors px-4 py-3 bg-gray-50 hover:bg-white" type="text" name="name" :value="old('name')" required autofocus placeholder="Ví dụ: Nguyễn Văn A" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition-colors px-4 py-3 bg-gray-50 hover:bg-white" type="email" name="email" :value="old('email')" required placeholder="nguyenvana@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <input id="password" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition-colors px-4 py-3 bg-gray-50 hover:bg-white" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                        <input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition-colors px-4 py-3 bg-gray-50 hover:bg-white" type="password" name="password_confirmation" required placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200">
                        Đăng ký tài khoản
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-500 transition-colors">Đăng nhập ngay</a>
                </p>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80" alt="Travel Registration" class="absolute inset-0 w-full h-full object-cover rounded-l-[3rem] shadow-2xl z-0">
            <div class="absolute inset-0 bg-gradient-to-t from-teal-900/80 to-transparent rounded-l-[3rem] z-10"></div>
            
            <div class="relative z-20 text-center px-12 mt-auto pb-24">
                <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight drop-shadow-lg">Mở khóa thế giới<br/>của riêng bạn</h1>
                <p class="text-lg text-teal-50 font-medium drop-shadow-md">Tham gia cùng chúng tôi để tạo nên những kỷ niệm khó quên.</p>
            </div>
        </div>
        
    </div>
</body>
</html>