@vite(['resources/css/app.css','resources/js/app.js'])

<x-posts-layout title="Post editor" :authorsPostCount="$authorsPostCount" :categoriesPostCount="$categoriesPostCount">
    <form action="{{ route("posts.update", $post)}}" method="POST">
        @csrf
        @method("PUT")
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ old("title", $post->title) }}"></br>
        @error('title')
            <div>{{ $message }}</div>
        @enderror

        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10">{{ old("content", $post->content) }}</textarea></br>
        @error("content")
            <div>{{ $message }}</div>
        @enderror

        <label for="public">Public</label>
        <input type="checkbox" name="public" id="public" {{ old('public', $post->public) ? 'checked' : ''}}></br>
        @error("public")
            <div>{{ $message }}</div>
        @enderror

        <input type="datetime-local" name="date" id="date" value="{{ old('date', $post->date) }}"></br>

        <button type="submit">Edit</button>
    </form>
</x-posts-layout>
