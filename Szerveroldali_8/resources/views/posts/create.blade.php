@vite(['resources/css/app.css','resources/js/app.js'])

<x-posts-layout title="Post editor" :authorsPostCount="$authorsPostCount" :categoriesPostCount="$categoriesPostCount">
    <form action="{{ route("posts.store")}}" method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ old("title") }}"></br>
        @error('title')
            <div>{{ $message }}</div>
        @enderror

        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10">{{ old("content") }}</textarea></br>
        @error("content")
            <div>{{ $message }}</div>
        @enderror

        <label for="public">Public</label>
        <input type="checkbox" name="public" id="public" {{ old('public') ? 'checked' : ''}}></br>
        @error("public")
            <div>{{ $message }}</div>
        @enderror

        <button type="submit">Create</button>
    </form>
</x-posts-layout>
