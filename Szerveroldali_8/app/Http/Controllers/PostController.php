<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

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
        if (Auth::user()->cannot('create', Post::class)) {
            Session::flash("error", "You are not allowed to create a post");
            return redirect()->route('posts.index');
        }

        return view('posts.create', ['categories' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->get(),
                                    'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
                                    'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->cannot('create', Post::class)) {
            Session::flash("error", "You are not allowed to create a post");
            return redirect()->route('posts.index');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:3|unique:posts',
            'content' => 'required|max:10000|min:3',
            'date' => 'nullable|date',
            'public' => 'nullable',
            'categories' => 'nullable|array|exists:categories,id',
            'image' => 'nullable|image'
        ],
        [
            'title.required' => 'A cím megadása kötelező!',
            'title.max' => 'A cím maximum 255 karakter hosszú lehet!',
            'title.min' => 'A cím minimum 3 karakter hosszú kell legyen!',
            'title.unique' => "A címnek egyedinek kell lennie!",
            'content.required' => 'A tartalom megadása kötelező!',
            'content.max' => 'A tartalom maximum 10000 karakter hosszú lehet!',
            'content.min' => 'A tartalom minimum 3 karakter hosszú kell legyen!',
            'date.date' => 'A dátum formátuma nem megfelelő!',
            'categories.array' => 'A kategóriák formátuma nem megfelelő!',
            'categories.exists' => 'A kategóriák közül legalább egy nem létezik!',
            'image.image' => 'A kép formátuma nem megfelelő!'
        ]);

        if (!isset($validated['date'])) { $validated['date'] = now(); }

        $validated['public'] = $request->has('public');
        //$valudated['public'] = isset($request->public);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fname = $file->hashName();
            Storage::disk('public')->put('images/' . $fname, $file->get());
            $validated['imagename'] = $fname;
        }

        $post = Post::make($validated);

        $post->author()->associate(Auth::user());

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

        if (Auth::user()->cannot('viewAny', $post)) {
            Session::flash("error", "You are not allowed to view the post");
            return redirect()->route('posts.index');
        }

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

        /*
        if (Gate::denies('edit-post', $post)) {
            Session::flash("error", "You are not allowed to edit this post");
            return redirect()->route('posts.index');
        }
        */

        if (!$post) {
            Session::flash("error", "Post not found");
            return redirect()->route('posts.index');
        }

        return view('posts.edit', ['post' => $post,
                                   'categories' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->get(),
                                   'authorsPostCount' => User::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get(),
                                   'categoriesPostCount' => Category ::withCount(['posts' => function ($query) { $query->where('public', true); }])->orderBy('posts_count', 'desc')->limit(8)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|min:3|unique:posts,title,' . $id,
            'content' => 'required|max:10000|min:3',
            'date' => 'nullable|date',
            'public' => 'nullable',
            'categories' => 'nullable|array|exists:categories,id',
            'image' => 'nullable|image'
        ],
        [
            'title.required' => 'A cím megadása kötelező!',
            'title.max' => 'A cím maximum 255 karakter hosszú lehet!',
            'title.min' => 'A cím minimum 3 karakter hosszú kell legyen!',
            'title.unique' => "A címnek egyedinek kell lennie!",
            'content.required' => 'A tartalom megadása kötelező!',
            'content.max' => 'A tartalom maximum 10000 karakter hosszú lehet!',
            'content.min' => 'A tartalom minimum 3 karakter hosszú kell legyen!',
            'date.date' => 'A dátum formátuma nem megfelelő!',
            'categories.array' => 'A kategóriák formátuma nem megfelelő!',
            'categories.exists' => 'A kategóriák közül legalább egy nem létezik!',
            'image.image' => 'A kép formátuma nem megfelelő!'
        ]);

        if (!$validated['date']) { $validated['date'] = now(); }

        $post = Post::find($id);

        if ($post->imagename != null && $request -> hasFile('image')) {
            Storage::disk('public')->delete('images/' . $post->imagename);
        }

        if ($request -> hasFile('image')){
            $file = $request -> file('image');
            $fname = $file -> hashName();
            Storage::disk('public') -> put('images/' . $fname, $file -> get());
            $validated['imagename'] = $fname;
        }
        else
        {
            $validated['imagename'] = $post->imagename;
        }

        /*
        if (Gate::denies('edit-post', $post)) {
            Session::flash("error", "You are not allowed to edit this post");
            return redirect()->route('posts.index');
        }
        */

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

        if ($post->imagename) {
            Storage::disk('public')->delete('images/' . $post->imagename);
        }

        $post->delete();

        Session::flash("success", "Post deleted successfully");

        return redirect()->route('posts.index');
    }
}
