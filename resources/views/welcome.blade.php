<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hệ thống Kế hoạch Du lịch</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans">
    <div class="relative bg-white overflow-hidden h-screen">
        <div class="max-w-7xl mx-auto h-full">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 h-full flex flex-col justify-center">
                
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                    <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                        <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                            <div class="flex items-center justify-between w-full md:w-auto">
                                <a href="#">
                                    <span class="sr-only">Travel App</span>
                                    <svg class="h-8 w-auto sm:h-10 text-teal-600 hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </a>
                            </div>
                        </div>
                        
                        <div class="hidden md:block md:ml-10 md:pr-4 md:space-x-8">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="font-bold text-teal-600 hover:text-teal-900 border-b-2 border-teal-600 pb-1">Vào Dashboard &rarr;</a>
                                @else
                                    <a href="{{ route('login') }}" class="font-medium text-gray-500 hover:text-gray-900 transition-colors">Đăng nhập</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-900 transition-colors">Tạo tài khoản</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </nav>
                </div>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Khám phá thế giới</span>
                            <span class="block text-teal-600 xl:inline mt-2">theo cách của bạn</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Hệ thống quản lý lịch trình du lịch cá nhân thông minh. Dễ dàng lên kế hoạch, lưu trữ địa điểm và bắt đầu chuyến đi mơ ước của bạn chỉ với vài cú click chuột.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            @if (Route::has('login'))
                                @auth
                                    <div class="rounded-md shadow">
                                        <a href="{{ url('/dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-teal-600 hover:bg-teal-700 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1">
                                            Tiếp tục hành trình
                                        </a>
                                    </div>
                                @else
                                    <div class="rounded-md shadow">
                                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-teal-600 hover:bg-teal-700 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1">
                                            Bắt đầu miễn phí
                                        </a>
                                    </div>
                                    <div class="mt-3 sm:mt-0 sm:ml-3">
                                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-teal-800 bg-teal-100 hover:bg-teal-200 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1">
                                            Đăng nhập
                                        </a>
                                    </div>
                                @endauth
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>
        
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=2021&q=80" alt="Travel background image">
        </div>
    </div>
</body>
</html>