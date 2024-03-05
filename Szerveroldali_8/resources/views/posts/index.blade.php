<script src="https://cdn.tailwindcss.com"></script><script src="https://cdn.tailwindcss.com"></script>

<div class="gap-4 flex flex-wrap">
    @foreach ($posts as $post)
    <div class="flex-auto w-96 p-4 shadow-md rounded-lg bg-gray-200">
        <a href="{{ route('posts.show', $post->id) }}">
            <h2 class="truncate font-semibold text-lg mb-2">{{ $post->title }}</h2>
            <p>{{ $post->content}}</p>
        </a>
    </div>
    @endforeach
</div>
