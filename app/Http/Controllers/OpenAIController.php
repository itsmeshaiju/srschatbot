<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

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

return response()->json($data['choices'][0]['message'], 200, array(), JSON_PRETTY_PRINT);
     }
}