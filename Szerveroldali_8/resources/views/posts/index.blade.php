@vite(['resources/css/app.css','resources/js/app.js'])

<x-posts-layout title="Posts" :highlightposts="$highlightposts">

<div class="my-3 gap-10 flex flex-wrap">
    @foreach ($posts as $post)
    @include('posts.partials.card')
    @endforeach
</div>

</x-posts-layout>
