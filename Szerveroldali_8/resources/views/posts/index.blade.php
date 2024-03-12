@vite(['resources/css/app.css','resources/js/app.js'])

<div class="w-full" data-carousel="slide">
    <!-- Carousel wrapper -->
    <div class="m-3 relative h-56 overflow-hidden rounded-lg">
        @foreach ($highlightposts as $post)
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <div class="rounded-lg p-5 bg-gray-700 shadow-md">
                <a href="{{ route('posts.show', $post->id) }}" class="text-gray-300 hover:text-gray-500 transition duration-500 ease-in-out">
                    <h2 class="truncate font-semibold text-lg mb-2">{{ $post->title }}</h2>
                    <div class="h-24 text-justify overflow-hidden relative">
                        <div class="absolute text-justify bottom-0 left-0 w-full h-10 bg-gradient-to-t from-gray-700 to-transparent"></div>
                        {{ $post->content }}
                    </div>
                </a>
                <div class="text-sm text-gray-100 mt-2">Posted by <a href="{{ route('users.show', $post->author->id) }}" class="font-semibold text-gray-400 hover:text-gray-800 transition duration-200 ease-in-out">{{ $post->author->name }}</a> on {{ $post->date }}</div>
                <div class="mt-2">
                    @foreach ($post->categories as $category)
                        <a href="{{ route('categories.show', $category->id) }}" class="inline-block px-2 py-1 rounded-full text-xs font-semibold text-gray-900 mt-1 mr-2 hover:text-gray-500 transition duration-200 ease-in-out" style="background-color: {{ $category->color }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        @endforeach
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            @foreach ($highlightposts as $post)
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="{{ $post->title }}" data-carousel-slide-to="{{ $loop->index }}"></button>
            @endforeach
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
</div>

<div class="my-3 gap-10 flex flex-wrap">
    @foreach ($posts as $post)
    @include('posts.partials.card')
    @endforeach
</div>
