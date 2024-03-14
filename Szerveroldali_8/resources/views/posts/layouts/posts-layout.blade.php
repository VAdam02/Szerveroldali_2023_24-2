<x-general-layout :title="$title">
    @if (isset($highlightposts))
        @include('posts.partials.carousel')
    @endif

    <div class="flex flex-wrap gap-10 p-5 my-3">
        <!-- Content -->
        <div class="grow lg:w-96 w-full">
            {{ $slot }}
        </div>

        <!-- Sidebar -->
        @if (isset($authorsPostCount) || isset($categoriesPostCount))
            <div class="flex flex-wrap gap-10 h-fit w-full lg:w-96">
                @if (isset($authorsPostCount))
                    <div class="mx-auto lg:mx-0 lg:w-full">
                        <h1 class="font-semibold mt-5 mb-3 text-3xl">Top authors</h1>
                        @include('general.table', [
                            'title' => 'Top authors',
                            'tableHeaders' => ['Name', 'Posts'],
                            'tableData' => $authorsPostCount->map(fn($author) => [$author->name, $author->posts_count])->toArray()
                        ])
                    </div>
                @endif

                @if (isset($categoriesPostCount))
                    <div class="mx-auto lg:mx-0 lg:w-full">
                        <h1 class="font-semibold mt-5 mb-3 text-3xl">Top categories</h1>
                        @include('general.table', [
                            'title' => 'Top categories',
                            'tableHeaders' => ['Name', 'Posts'],
                            'tableData' => $categoriesPostCount->map(fn($category) => [$category->name, $category->posts_count])->toArray()
                        ])
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-general-layout>
