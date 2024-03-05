<script src="https://cdn.tailwindcss.com"></script><script src="https://cdn.tailwindcss.com"></script>

<div class="gap-4 flex flex-wrap">
    @foreach ($posts as $post)
    <div class="flex-auto w-96 p-4 shadow-md rounded-lg bg-gray-200 hover:scale-105 transform transition duration-300 ease-in-out">
        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-gray-500">
            <h2 class="truncate font-semibold text-lg mb-2">{{ $post->title }}</h2>
            <div class="h-24 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-gray-200 to-transparent"></div>
                <p>{{ $post->content}}</p>
            </div>
        </a>
        <div class="mt-2">
            Posted by <a href="{{ route('users.show', $post->author->id) }}" class="font-semibold text-blue-500 hover:text-blue-900 transition duration-1000 ease-in-out">{{ $post->author->name }}</a> on {{ $post->date }}
        </div>
        <div class="mt-2">
            @foreach ($post->categories as $category)
            <a href="{{ route('categories.show', $category->id) }}" class="inline-block px-2 py-1 rounded-full text-xs font-semibold text gray-900 mt-1 mx-1" style="background-color: {{ $category->color }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
