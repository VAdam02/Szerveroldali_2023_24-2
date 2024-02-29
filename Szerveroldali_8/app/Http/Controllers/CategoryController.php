<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "List all of the categories<br>" . Category::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return "Create a new category";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'color' => 'required'
        ]);

        $category = Category::create($validate);

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) { return response("Category $id not ound", 404); }

        return "Show the category with id $id<br>" . $category->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        if (!$category) { return response("Category $id not ound", 404); }

        return "Edit the category with id $id<br>" . $category->toJson();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'color' => 'required'
        ]);

        $category = Category::find($id);

        if (!$category) { return response("Category $id not ound", 404); }

        $category->update($validate);

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) { return response("Category $id not ound", 404); }

        $category->delete();

        return redirect()->route('categories.index');
    }
}
