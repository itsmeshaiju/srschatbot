<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;

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
        Category::create([
            'category_name'=>'Web Application'
         ]);
         Category::create([
            'category_name'=>'Mobile Application'
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'What is the purpose of the application?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Do you have any technology preferences?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'What are the main goals and objectives?',
         ]);
         
         Question::create([
            'cat_id'=>1,
            'question_name'=>'Who are the intended users of the application?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'What are the core features and functionalities required in the application?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Are there any specific workflows or processes that need to be implemented?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'How should user registration and authentication be handled?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'What types of user roles and permissions should be implemented?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Are there any specific security requirements, such as data encryption, secure connections (HTTPS/SSL), or user authorization?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Do you have any preferences for data storage, such as databases or third-party services?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Does the web application need to integrate with any external systems or APIs?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Are there any specific third-party services or platforms that need to be integrated?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Are there any specific performance requirements, such as response times or concurrent user capacity?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Do you anticipate high traffic or concurrent user loads?',
         ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Do you have any preferences or constraints regarding the deployment environment or hosting platform?',
         ]);
    }
}
