<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use App\Models\gptQuestionAnswer;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;
class apiTestController extends Controller
{


public function fetchChatCompletions(){
    $prompt = "print onelakh words";


    // Create a new Guzzle HTTP client
    $client = new Client([
        'curl' => [
            CURLOPT_CAINFO => base_path('resources/assets/cacert.pem')
        ]
    ]);

// Set the API endpoint URL
$url = 'https://api.openai.com/v1/completions';

// Set the request headers
$headers = [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), // Replace $OPENAI_API_KEY with your actual API key
];

// Set the request body
$data = [
    'model' => 'text-davinci-003',
    'prompt' => $prompt,
    'temperature' => 1,
    'max_tokens' => 2048,
    'top_p' => 1,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
];

// Send the POST request to the API
// $response = $client->post($url, [
//     'headers' => $headers,
//     'json' => $data,
// ]);


$promises = [
    $client->postAsync($url, [
        'headers' => $headers,
        'json' => $data,
    ]),
];

$results = Utils::unwrap($promises);
$response = $results[0]->getBody()->getContents();

// Get the response body
$data = json_decode($response, true);
$content = $data['choices'][0];
 dd($content);
}







function fetchChatCompletions11($prompt, $page = 1, $completions = [])
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
