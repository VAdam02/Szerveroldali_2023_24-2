@vite(['resources/css/app.css','resources/js/app.js'])

<x-posts-layout title="Post editor" :authorsPostCount="$authorsPostCount" :categoriesPostCount="$categoriesPostCount">
    @include('posts.partials.card')

    <a href="{{ route('posts.edit', $post) }}">Edit</a>
    <form action="{{ route('posts.destroy', $post) }}" method='POST'>
        @method("DELETE")
        <button type="submit">Delete</button>
    </form>
</x-posts-layout>
