<script src="https://cdn.tailwindcss.com"></script><script src="https://cdn.tailwindcss.com"></script>

<div class="gap-4 flex flex-wrap">
    @foreach ($posts as $post)
    <div class="flex-auto w-96 p-4 shadow-md rounded-lg bg-gray-200">
        <a href="{{ route('posts.show', $post->id) }}">
            <h2 class="truncate font-semibold text-lg mb-2">{{ $post->title }}</h2>
            <div class="h-24 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-gray-200 to-transparent"></div>
                <p>{{ $post->content}}</p>
            </div>
        </a>
        <div>
            Posted by <a href="{{ route('users.show', $post->author->id) }}">{{ $post->author->name }}</a> on {{ $post->date }}
        </div>
        <div>
            @foreach ($post->categories as $category)
            <a href="{{ route('categories.show', $category->id) }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
