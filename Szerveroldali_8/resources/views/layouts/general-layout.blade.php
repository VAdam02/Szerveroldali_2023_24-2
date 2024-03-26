<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gray-800 text-white p-5">
        <div class="mx-auto">
            <div class="flex justify-between items-center">
                <div class="w-1/3 text-start">
                    <a class="text-2xl mx-2" href="{{ route('posts.index') }}">Posts</a>
                    <a class="text-2xl mx-2" href="{{ route('users.index') }}">Users</a>
                    <a class="text-2xl mx-2" href="{{ route('categories.index') }}">Categories</a>
                </div>
                <div class="w-1/3 text-center">
                    <a class="text-3xl font-semibold">{{ $title }}</a>
                </div>
                <div class="w-1/3 text-end">
                    @auth
                        <span>{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="post" class="inline">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="text-white">Login</a>
                        <a href="{{ route('register') }}" class="text-white">Register</a>
                    @endguest
                </div>
            </div>
        </div>
        @if (session('last_visited'))
            <p>Legutoljára a {{ session('last_visited') }}. posztot nézted meg</p>
        @endif
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif
    </header>

    {{ $slot }}

    <footer class="bg-gray-800 text-white py-5 px-5 text-center">
        <div class="mx-auto">
            <p>&copy; 2024 Blog - All rights reserved</p>
        </div>
    </footer>
</body>
</html>
