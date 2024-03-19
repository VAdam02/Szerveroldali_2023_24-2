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
            <h1 class="text-3xl font-semibold">{{ $title }}</h1>
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
