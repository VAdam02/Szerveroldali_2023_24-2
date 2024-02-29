<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "List of all users<br>" . User::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return "Form for creating a new user";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //TODO
        return response('User created', 201);
    }

    /**User create
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //return "Show user with id $id" . User::find($id)->toJson();
        return "Show user with id $id<br>" . User::findOrFail($id)->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return "Form for editing user with id $id<br>" . User::findOrFail($id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //TODO
        return response("User $id updated", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //TODO
        return response("User $id deleted", 200);
    }
}
