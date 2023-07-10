<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use App\Models\gptQuestionAnswer;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Http\JsonResponse;
class apiTestController extends Controller
{


public function fetchChatCompletions(){

    $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();
        $content = "";
        foreach ($qtArray as $data) {
            $data = json_decode($data->question_and_answer, TRUE);

            $content .= $data['answer'] . ' ' . $data['question'].' ';
        }
     
        $content .= ' create srs document';
       
        $prompt = $content;
      

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
    'temperature' => 0.4,
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
$content = $data['choices'][0]['text'];
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
  $prompt = 'What is the purpose of the application? hi Do you have any technology preferences? e commerce What are the main goals and objectives? react js frontend and php backend Who are the intended users of the application? taking orders and deliverying products What are the core features and functionalities required in the application? admin customer vendor Are there any specific workflows or processes that need to be implemented? order management,delivery management category management stock management user management How should user registration and authentication be handled? no What types of user roles and permissions should be implemented? yes Are there any specific security requirements, such as data encryption, secure connections (HTTPS/SSL), or user authorization? yes Do you have any preferences for data storage, such as databases or third-party services? yes Does the web application need to integrate with any external systems or APIs? mysql Are there any specific third-party services or platforms that need to be integrated? yes payament gawteway Are there any specific performance requirements, such as response times or concurrent user capacity? thirty party delievery apis Do you anticipate high traffic or concurrent user loads? no Do you have any
   preferences or constraints regarding the deployment environment or hosting platform? yes  create srs document';


    



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





public function botData(): JsonResponse
    {
        $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();
        $content = "";
        foreach ($qtArray as $data) {
            $data = json_decode($data->question_and_answer, TRUE);

            $content .= $data['answer'] . ' ' . $data['question'];
        }

        $content .= '  create srs document';
        // dd( $content);
//         $content = 'What is the purpose of the application? hi Do you have any technology preferences? e commerce What are the main goals and objectives? react js frontend and php backend Who are the intended users of the application? taking orders and deliverying products What are the core features and functionalities required in the application? admin customer vendor Are there any specific workflows or processes that need to be implemented? order management,delivery management category management stock management user management How should user registration and authentication be handled? no What types of user roles and permissions should be implemented? yes Are there any specific security requirements, such as data encryption, secure connections (HTTPS/SSL), or user authorization? yes Do you have any preferences for data storage, such as databases or third-party services? yes Does the web application need to integrate with any external systems or APIs? mysql Are there any specific third-party services or platforms that need to be integrated? yes payament gawteway Are there any specific performance requirements, such as response times or concurrent user capacity? thirty party delievery apis Do you anticipate high traffic or concurrent user loads? no Do you have any
//    preferences or constraints regarding the deployment environment or hosting platform? yes  create srs document';


    
        $client = new Client([
            'curl' => [
                CURLOPT_CAINFO => base_path('resources/assets/cacert.pem')
            ]
        ]);

        $response = $client->post("https://api.openai.com/v1/chat/completions", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ],

            'json' => [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    [
                        "role" => "user",
                        "content" => $content
                    ]

                ],
                'temperature' => 1,

                "max_tokens" => 200,
                'n' => 10, // Set the desired pagination count
                'stop' => ['\n'],

                "top_p" => 1.0,

                "frequency_penalty" => 0.52,

                "presence_penalty" => 0.5,

                "stop" => ["11."],

            ]



        ]);
        $data = json_decode($response->getBody(), true);
       
        // dd($data);

        $content = $data['choices'][0]['message']['content'];
        dd($content);
        $data = [

            'question' => 'create srs document',

            'answer' => $content



        ];

        $json_data = json_encode($data);
        gptQuestionAnswer::create([

            'question_and_answer' => $json_data,

            'user_id' => auth()->user()->id,



        ]);

        $content = str_replace('```', " ", $content);

        $data['choices'][0]['message']['content'] = nl2br($content);

      
        $question = [
            'question_name' => $data['choices'][0]['message']['content'] . '<br> can we send via mail in your registered mail ?',
            'id' => 'send_mail'
        ];


        return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
    }




}
