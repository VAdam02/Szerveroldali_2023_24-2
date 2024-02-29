<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class HelloController extends Controller
{
    public function some_method() {
        return 'Hello World 2!';
    }

    public function listAll() {

        //$people = Person::all();
        //$people = Person::where('age', '>', 40)->get();
        //$people = Person::where('age', '>', 40)->orderBy('age', 'asc')->get();
        //$people = Person::orderBy('age', 'asc')->where('age', '>', 40)->get();
        //$people = Person::where('age', '>', 30)->limit(5)->get();
        $people = Person::where('age', '>', 30)->paginate(5);

        return $people->toJson();
    }
}
