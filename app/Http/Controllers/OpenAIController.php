<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\gptQuestionAnswer;
use App\Mail\BotDataMail;

class OpenAIController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response(request )
     */

    public function index()
    {
       
        if (Auth::check()) {
            return view('chatWindow');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
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
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
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
        $content = $data['choices'][0]['message']['content'];

        
        

        
        $data = [
            'question' => $request->user_input,
            'answer' => $content
            
        ];
        $json_data = json_encode($data);

        gptQuestionAnswer::create([
            'question_and_answer' => $json_data,
            'user_id' => auth()->user()->id,
            
          ]);

        // $modifiedString = str_replace($wordToFind, $wordToFind . " " . $stringToInsert, $originalString);
        // $modifiedString = str_replace($wordToFind, $wordToFind . " " . $stringToInsert, $originalString);
        // substr_replace($oldstr, $str_to_insert, $pos, 0);
        $content = str_replace('```', " ", $content);



        $data['choices'][0]['message']['content'] = nl2br($content);

          // Generate PDF
        $questions = Question::find($questions->id);
        $pdfController = new PdfController();
        $pdf = $pdfController->generatePDF($questions);

        // Send email with PDF attachment
        Mail::to('sinfolitz@gmail.com')->send(new BotDataMail($questions, $pdf));

        return response()->json($data['choices'][0]['message'], 200, array(), JSON_PRETTY_PRINT);
    }

    
}