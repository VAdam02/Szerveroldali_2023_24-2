<p>Hello world</p>

@foreach ($posts as $post)
<a href="{{ route('posts.show', $post->id) }}">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content}}</p>
</div>
@endforeach
