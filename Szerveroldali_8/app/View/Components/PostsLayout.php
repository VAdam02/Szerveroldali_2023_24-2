<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PostsLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $title,
        public $authorsPostCount = null, public $categoriesPostCount = null,
        public $highlightposts = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('posts.layouts.posts-layout');
    }
}
