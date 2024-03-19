<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'title' => 'required',
            'content' => 'required',
            'date' => 'nullable',
            'public' => 'nullable'
        ]);

        if (!isset($validated['date'])) { $validated['date'] = now(); }

        $validated['public'] = $request->has('public');
        //$valudated['public'] = isset($request->public);

        $post = Post::make($validated);

        //TODO
        $post->author()->associate(User::find(1));

        $post->save();

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) { return response("Post $id not found", 404); }

        return "Show the post with id $id<br>" . $post->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);

        if (!$post) { return response("Post $id not found", 404); }

        return "Edit the post with id $id<br>" . $post->toJson();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'date' => 'nullable',
            'public' => 'required'
        ]);

        if (!$validated['date']) { $validated['date'] = now(); }

        $post = Post::find($id);

        if (!$post) { return response("Post $id not found", 404); }

        $post->update($validated);

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) { return response("Post $id not found", 404); }

        $post->delete();

        return redirect()->route('posts.index');
    }
}
