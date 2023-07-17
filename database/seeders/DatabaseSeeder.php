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
        // Category::create([
        //     'category_name'=>'Web Application'
        //  ]);
        //  Category::create([
        //     'category_name'=>'Mobile Application'
        //  ]);

         Question::create([
            'cat_id'=>1,
            'question_name'=>'Hi
            Good day
            This is the Digital Twin of Arjun Varma, you can call me DT for short.',
         ]);
         Question::create([
          'cat_id'=>1,
          'question_name'=>'What should I call you?',
       ]);
       Question::create([
        'cat_id'=>1,
        'question_name'=>"My role is to ask you questions, listen carefully to your answers, and gather all the important details. Your job is to share your thoughts, needs, and any challenges you're facing related to software or technology.

        Think of it as a friendly conversation where you get to tell me everything you want and everything that's bothering you. You can explain what kind of software you dream of having or the specific issues you want to get rid of. The more information you share, the better equipped we'll be to find the right solution


        We need your insights to understand your requirements or pain points. Once we have all the necessary details, our team of experts will put their skills to work. They'll use the information we gather to design and build the software or address the technical problems you're facing
        
        ",
     ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Do you have any technology preferences?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'What are the main goals and objectives?',
   //       ]);
         
   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Who are the intended users of the application?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'What are the core features and functionalities required in the application?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Are there any specific workflows or processes that need to be implemented?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'How should user registration and authentication be handled?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'What types of user roles and permissions should be implemented?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Are there any specific security requirements, such as data encryption, secure connections (HTTPS/SSL), or user authorization?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Do you have any preferences for data storage, such as databases or third-party services?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Does the web application need to integrate with any external systems or APIs?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Are there any specific third-party services or platforms that need to be integrated?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Are there any specific performance requirements, such as response times or concurrent user capacity?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Do you anticipate high traffic or concurrent user loads?',
   //       ]);

   //       Question::create([
   //          'cat_id'=>1,
   //          'question_name'=>'Do you have any preferences or constraints regarding the deployment environment or hosting platform?',
   //       ]);
     }
}
