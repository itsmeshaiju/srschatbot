<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

use GuzzleHttp\Client;
class OpenAIController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response(request )
     */

    public function index()
    {
    
    return view('chatWindow');
    }

    // public function botData(Request $request): JsonResponse
    // {
    // // Your existing code...

    // // Get the content from the response
    // $content = $data['choices'][0]['message']['content'];

    // // Send the content as an email
    // Mail::send([], [], function (Message $message) use ($content) {
    //     $message->to('athirachinu378@gmail.com') // Set the recipient email address
    //         ->subject('Bot Data') // Set the email subject
    //         ->setBody($content); // Set the email body as the content
    // });

    // // Return the response as JSON
    // return response()->json($data['choices'][0]['message'], 200, [], JSON_PRETTY_PRINT);
    // }


    public function botData(Request $request): JsonResponse
    {
       
       
    $search = $request->user_input;
    $client = new Client([
    'curl' => [
        CURLOPT_CAINFO => base_path('resources/assets/cacert.pem')
    ]
    ]);

    $response = $client->post("https://api.openai.com/v1/chat/completions", [
    'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
    ],
    'json' => [
        "model" => "gpt-3.5-turbo",
        'messages' => [
            [
                "role" => "user",
                "content" => $search
            ]
        ],
        'temperature' => 0.5,
        "max_tokens" => 200,
        "top_p" => 1.0,
        "frequency_penalty" => 0.52,
        "presence_penalty" => 0.5,
        "stop" => ["11."],
    ]
    ]);

    $data = json_decode($response->getBody(), true);
    $contant = $data['choices'][0]['message']['content'];
    // $modifiedString = str_replace($wordToFind, $wordToFind . " " . $stringToInsert, $originalString);
    // $modifiedString = str_replace($wordToFind, $wordToFind . " " . $stringToInsert, $originalString);
    // substr_replace($oldstr, $str_to_insert, $pos, 0);
    $contant = str_replace('```'," ",$contant);



    $data['choices'][0]['message']['content'] = nl2br($contant);

    $content = $data['choices'][0]['message']['content'];

    // Prepare the email data
    $to = 'athirachinu378@gmail.com'; // Set the recipient email address
    $subject = 'Bot Data'; // Set the email subject
    $body = $content; // Set the email body as the content

    // Send the email
    Mail::raw($body, function (Message $message) use ($to, $subject) {
        $message->to($to)
            ->subject($subject);
    });


    return response()->json($data['choices'][0]['message'], 200, [], JSON_PRETTY_PRINT);
    }
        
}