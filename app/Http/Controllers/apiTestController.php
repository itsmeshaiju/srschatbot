<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use App\Models\gptQuestionAnswer;
class apiTestController extends Controller
{


    function fetchChatCompletions11($prompt, $page = 1, $completions = [])
    {
    $page = '1'; // The page number you want to retrieve
    $messagesPerPage = '5'; // The number of messages per page
    
    $allMessages = []; // Array to store all the messages
    
    do {
        $response = Http::withOptions([
            'verify' => base_path('resources/assets/cacert.pem'),
        ])->withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ],
            'max_tokens' => 100, // Adjust the maximum number of tokens as needed
            'page' => $page,
            'n' => $messagesPerPage, // Number of messages to retrieve per page
        ]);
    
        $responseData = $response->json();
        dd('res',$responseData);
        // Check if the API response is successful
        if ($response->successful() && isset($responseData['choices'])) {
           
            $messages = $responseData['choices'][0]['message']['content'];
            $allMessages = array_merge($allMessages, $messages);
            $page++;
        } else {
            // Handle API error or unsuccessful response
            // You may want to log or display an error message
            break;
        }
        
    } 
   
    while ($responseData['choices'][0]['message']['role'] !== 'assistant');

   
}    




function fetchChatCompletions($prompt, $page = 1, $completions = [])
{

    // $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();
    // $content = "";
    // foreach ($qtArray as $data) {
    //     $data = json_decode($data->question_and_answer, TRUE);

    //     $content .= $data['question'] . ' ' . $data['answer'];
    // }

    // $content .= '  create srs document';
    $prompt = "What is the purpose of the web application?
    We need to create an B2C marketplace application named Esmartshope by using Magento.
What are the core features and functionalities required in the web application?
    Esmartshope is a B2C marketplace application. Here users can buy, sell and advertise their products and services. It's created a scalable multivendor application with gamification to drive product sales. If the vendor has a website, an API can be integrated with the website and products from the vendor’s website can be uploaded directly to the website. We have also created a wallet module in the marketplace for making payments from the wallet. At the time of playing the scratch card game, the game amount needed will be deducted from the customer’s wallet.
What types of user roles and permissions should be implemented?
    Admin,Area Manager,Branch Manager, Delivery boy
Do you have any preferences for data storage, such as databases or third-party services?
Yes. We can done it by using MySQL.
Are there any specific third-party services or platforms that need to be integrated?
   ChatGPT needs to be implemented
Are there any specific security requirements, such as data encryption, secure connections (HTTPS/SSL), or user authorization?
Yes. We need to implement SSL and user authorization.
Prepare an SRS Document";


    



    $response = Http::withOptions([
        'verify' => base_path('resources/assets/cacert.pem'),
    ])->
    withHeaders([
        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
    ])->post('https://api.openai.com/v1/chat/completions', [
        "model" => "gpt-3.5-turbo",
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        // 'page' => $page,
        'temperature' => 0.5,
        "max_tokens" => 200,
        // 'n' => 10, // Set the desired pagination count
        // 'stop' => ['\n'],

        "top_p" => 1.0,

        "frequency_penalty" => 0.52,

        "presence_penalty" => 0.5,
        "stream"=>false,
        // "stop" => ["11."],
        "stop" => null,
    ]);



    $responseData = $response->json();
     dd($responseData);
    if ($response->successful()) {
        $completions = array_merge($completions, $responseData['choices']);
        dd($responseData);
        // Check if there are more pages
        // if (isset($responseData['next_page'])) {
        //     return fetchChatCompletions($prompt, $page + 1, $completions);
        // }
    } else {
        // Handle error response
        // $response->status() contains the HTTP status code
        // $responseData contains the error message
        // Handle the error according to your needs
    }

    return new Collection($completions);
}



}
