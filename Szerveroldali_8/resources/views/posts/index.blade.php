@vite(['resources/css/app.css','resources/js/app.js'])

<x-general-layout title="Posts">

@include('posts.partials.carouser')

<div class="my-3 gap-10 flex flex-wrap">
    @foreach ($posts as $post)
    @include('posts.partials.card')
    @endforeach
</div>

</x-general-layout>
