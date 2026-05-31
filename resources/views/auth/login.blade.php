<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - Quản lý lịch trình</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased overflow-hidden bg-white">
    <div class="min-h-screen flex">
        
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Chào mừng trở lại! 👋</h2>
                    <p class="text-gray-500 text-sm">Đăng nhập để tiếp tục lên kế hoạch cho chuyến đi của bạn.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-3 bg-gray-50 hover:bg-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nguyenvana@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors font-medium" href="{{ route('password.request') }}">
                                    Quên mật khẩu?
                                </a>
                            @endif
                        </div>
                        <input id="password" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-3 bg-gray-50 hover:bg-white" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Đăng nhập hệ thống
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Tạo tài khoản mới</a>
                </p>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center">
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80" alt="Travel Illustration" class="absolute inset-0 w-full h-full object-cover rounded-l-[3rem] shadow-2xl z-0">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/80 to-transparent rounded-l-[3rem] z-10"></div>
            
            <div class="relative z-20 text-center px-12 mt-auto pb-24">
                <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight drop-shadow-lg">Hành trình vạn dặm <br/>bắt đầu từ đây</h1>
                <p class="text-lg text-indigo-50 font-medium drop-shadow-md">Lên kế hoạch, quản lý và lưu giữ những địa điểm tuyệt vời nhất của riêng bạn.</p>
            </div>
        </div>
        
    </div>
</body>
</html>