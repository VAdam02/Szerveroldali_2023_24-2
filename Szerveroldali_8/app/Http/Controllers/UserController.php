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
        try {
            $validated = $request->validate([
                "name" => "required",
                "email" => "required|unique:users",
                "age" => "required",
                "phone" => "nullable",
                "password" => "required"
            ]);

            //return response($validated, 200);

            $user = User::create($validated);

            return redirect()->route('users.show', ['user' => $user->id]);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
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
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|unique:users",
            "age" => "required",
            "phone" => "nullable",
            "password" => "required"
        ]);

        $user = User::findOrFail($id);
        //VAGY
        //$user = User::find($id);
        //if (!$user) { return response("User $id not found", 404); }

        $user->update($validated);

        return redirect()->route('users.show', ['user' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->softDelete();

        return redirect()->route('users.index');
    }
}
