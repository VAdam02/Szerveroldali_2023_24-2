@vite(['resources/css/app.css','resources/js/app.js'])
<x-posts-layout title="Posts" :highlightposts="$highlightposts" :authorsPostCount="$authorsPostCount" :categoriesPostCount="$categoriesPostCount">
    <h1 class="font-semibold mt-5 mb-3 text-3xl">Latest posts</h1>
    <!-- List of Posts -->
    <div class="my-3 gap-10 flex flex-wrap">
        @foreach ($posts as $post)
        @include('posts.partials.card')
        @endforeach
    </div>
    <!-- Pagination Links -->
    <div class="my-3">
        {{ $posts->links() }}
    </div>
</x-posts-layout>
