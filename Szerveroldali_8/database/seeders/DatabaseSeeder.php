<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Person::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            Person::factory()->create([
                'name' => "Test {$i}",
                'age' => 20 + $i
            ]);
        }

        User::factory(10)->create();
        for ($i = 0; $i < 10; $i++)
        {
            User::factory()->create([
                'name' => "name$i",
                'email' => "email$i@szerveroldali.hu",
                'age' => 20 + $i,
                'password' => "password$i"
            ]);
        }

        $categories = collect();
        $categoryCount = rand(10, 20);
        for ($i = 0; $i < $categoryCount; $i++) {
            $categories->push(Category::factory()->create([

            ]));
        }

        $posts = collect();
        $postCount = rand(30, 50);
        for ($i = 0; $i < $postCount; $i++) {
            $posts->push(Post::factory()->create([

            ]));
        }
    }
}
