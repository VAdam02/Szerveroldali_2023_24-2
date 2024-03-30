<div class="sm:w-96 w-full grow rounded-lg p-4 bg-gray-200 shadow-md hover:shadow-lg hover:scale-105 transform transition duration-300 ease-in-out">
    @if($post->imagename != null)
    <div class="relative w-full h-48 mb-4">
        <img src="{{ Storage::url('images/' . $post->imagename) }}" alt="{{ $post->title }}" class="w-full h-full object-cover rounded-lg">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-gray-900 opacity-50 rounded-lg"></div>
    </div>
    @endif
    <a href="{{ route('posts.show', $post->id) }}" class="hover:text-gray-500">
        <h2 class="truncate font-semibold text-lg mb-2">{{ $post->title }}</h2>
        <div class="h-48 text-justify overflow-hidden relative">
            <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-gray-200 to-transparent"></div>
            {{ $post->content }}
        </div>
    </a>
    <div class="text-sm text-gray-500 mt-2">Posted by <a href="{{ route('users.show', $post->author->id) }}" class="font-semibold text-gray-900 hover:text-gray-500">{{ $post->author->name }}</a> on {{ $post->date }}</div>
    <div class="mt-2">
        @foreach ($post->categories as $category)
            <a href="{{ route('categories.show', $category->id) }}" class="inline-block px-2 py-1 rounded-full text-xs font-semibold text-gray-900 mt-1 mr-2 hover:text-gray-500" style="background-color: {{ $category->color }}">{{ $category->name }}</a>
        @endforeach
    </div>
</div>
