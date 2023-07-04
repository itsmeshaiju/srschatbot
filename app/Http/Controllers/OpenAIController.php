<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Dompdf\Dompdf;

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

    // Get the question and content from the response
    $question = $request->input('user_input');
    $contant = $data['choices'][0]['message']['content'];

    // Prepare the response data
    $responseData = [
        'question' => $question,
        'content' => $contant,
    ];

    // Get the content from the response
    $contant = $data['choices'][0]['message']['content'];

    // Generate PDF using Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($question);
    $dompdf->loadHtml($contant);
    $dompdf->setPaper('A4');
    $dompdf->render();
    $pdfContent = $dompdf->output();

    // Send the PDF as an email attachment
    $recipient = 'athirachinu378@gmail.com'; // Set the recipient email address
    $subject = 'Bot Data'; // Set the email subject
    $fileName = 'bot_data.pdf'; // Set the filename for the PDF attachment

    Mail::raw('Please find attached the Bot Data PDF.', function (Message $message) use ($recipient, $subject, $pdfContent, $fileName) {
        $message->to($recipient)
            ->subject($subject)
            ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
    });



    return response()->json($data['choices'][0]['message'], 200, [], JSON_PRETTY_PRINT);
    }
        
}