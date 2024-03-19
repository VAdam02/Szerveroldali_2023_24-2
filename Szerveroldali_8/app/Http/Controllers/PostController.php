<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('posts.index', ['posts' => Post::orderBy('date', 'desc')->where('public', true)->with('author', 'categories')->paginate(12),
                                   'highlightposts' => Post::orderBy('date', 'desc')->where('public', true)->with('author', 'categories')->take(5)->get(),
                                   'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
                                   'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create', ['categories' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->get(),
                                    'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
                                    'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255|unique:posts',
            'content' => 'required',
            'date' => 'nullable|date',
            'public' => 'nullable'
        ],
        [
            'title.required' => 'A címet kötelező megadni',
            'title.min' => 'Tűl rövid',
            'title.max' => 'Túl hosszú',
            'title.unique' => 'Már létezik ilyen című bejegyzés',
            'content.required' => 'A tartalmat kötelező megadni',
            'public.nullable' => 'A publikálás formátuma nem megfelelő'
        ]);

        if (!isset($validated['date'])) { $validated['date'] = now(); }

        $validated['public'] = $request->has('public');
        //$valudated['public'] = isset($request->public);

        $post = Post::make($validated);

        //TODO
        $post->author()->associate(User::find(1));

        $post->save();

        Session::flash("success", "Post created successfully");

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            Session::flash("error", "Post not found");
            return redirect()->route('posts.index');
        }

        Session::flash('last_visited', $post->id);

        return view('posts.show', ['post' => $post,
        'highlightposts' => Post::orderBy('date', 'desc')->where('public', true)->with('author', 'categories')->take(5)->get(),
        'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
        'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            Session::flash("error", "Post not found");
            return redirect()->route('posts.index');
        }

        return view('posts.edit', ['post' => $post,
        'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
        'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]
    );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255|unique:posts,title,' . $id,
            'content' => 'required',
            'date' => 'nullable|date',
            'public' => 'nullable'
        ],
        [
            'title.required' => 'A címet kötelező megadni',
            'title.min' => 'Tűl rövid',
            'title.max' => 'Túl hosszú',
            'title.unique' => 'Már létezik ilyen című bejegyzés',
            'content.required' => 'A tartalmat kötelező megadni',
            'public.nullable' => 'A publikálás formátuma nem megfelelő'
        ]);

        if (!$validated['date']) { $validated['date'] = now(); }

        $post = Post::find($id);

        if (!$post) {
            Session::flash("error", "Post not found");
            return redirect()->route('posts.index');
        }

        $post->update($validated);

        Session::flash("success", "Post edited successfully");

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            Session::flash("error", "Post not found");
            return redirect()->route('posts.index');
        }

        $post->delete();

        Session::flash("success", "Post deleted successfully");

        return redirect()->route('posts.index');
    }
}
