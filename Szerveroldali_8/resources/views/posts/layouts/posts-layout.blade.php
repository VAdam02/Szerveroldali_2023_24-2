<x-general-layout :title="$title">
    @include('posts.partials.carouser')

    {{ $slot }}

    @include('posts.partials.table', ['tableHeaders' => ['Author', 'Posts'], 'tableData' => [['Author 1', 3], ['Author 2', 5]]])
</x-general-layout>
