<x-general-layout :title="$title">
    @include('posts.partials.carouser')

    {{ $slot }}
</x-general-layout>
