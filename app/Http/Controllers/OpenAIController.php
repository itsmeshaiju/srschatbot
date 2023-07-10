<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\gptQuestionAnswer;
use App\Models\Question;
use GuzzleHttp\Promise\Utils;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\MailController;

class OpenAIController extends Controller
{


    // show chat window
    public function index(Request $request)
    {
        return view('chatWindow');
    }



    //ajax request for getting questions
    public function getQuestions(Request $request)
    {

        //this checking ask question count for is it last question or not
        $qtCount = Question::count();
        if ($request->qt_count == $qtCount) {
            $question = [

                'question_name' => 'can we procceed ?',
                'id' => 0
            ];

            return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
        }

        //this block for send contant to chatgpt
        if (isset($request->q_id) && $request->q_id == 0) {

            $botData   =  $this->botData();
            return $botData;
        }

        //this block for srs document convert to pdf and send via mail 
        if (isset($request->q_id) && $request->q_id == 'send_mail') {


            $name = "SRSDocument_" . date("ymdhis") . '.pdf';

            $pdfController = new PdfController();
            $pdfContent = $pdfController->generatePDF($name);
            $mailController = new MailController();
            $mailController->sendMail($pdfContent);
            gptQuestionAnswer::where('user_id', auth()->user()->id)->delete();
            $question = [
                'question_name' => 'we will share you pdf shortly..have a nice day',
                'id' => 'shared_mail'
            ];

            return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
        }


        //getting ask question based on id 
        $id = (isset($request->q_id) ? $request->q_id  : 0);

        $question = Question::select('id', 'question_name')->where('id', '>', $id)->orderBy('id')->first();

        $data = [
            'answer' => $request->user_input,
            'question' => $question->question_name

        ];
        $json_data = json_encode($data);

        gptQuestionAnswer::create([
            'question_and_answer' => $json_data,
            'user_id' => auth()->user()->id,

        ]);


        return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
    }







    //fetch data to chat gpt
    public function botData(): JsonResponse
    {
        $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();
        $content = "";
        foreach ($qtArray as $data) {
            $data = json_decode($data->question_and_answer, TRUE);

            $content .= $data['answer'] . ' ' . $data['question'];
        }

        $content .= '  create srs document';
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
                'temperature' => 0.5,
                'max_tokens' => 2048,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ]



        ]);
        $data = json_decode($response->getBody(), true);

        // dd($data);

        $content = $data['choices'][0]['message']['content'];

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




    // //chatgpt api function  
    // public function botData11(): JsonResponse
    // {
    //     $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();
    //     $content = "";
    //     foreach ($qtArray as $data) {
    //         $data = json_decode($data->question_and_answer, TRUE);

    //         $content .= $data['question'] . ' ' . $data['answer'];
    //     }

    //     $content .= '  create srs document';



    //     // Create a new Guzzle HTTP client
    //     $client = new Client([
    //         'curl' => [
    //             CURLOPT_CAINFO => base_path('resources/assets/cacert.pem')
    //         ]
    //     ]);

    //     // Set the API endpoint URL
    //     $url = 'https://api.openai.com/v1/completions';

    //     // Set the request headers
    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), // Replace $OPENAI_API_KEY with your actual API key
    //     ];

    //     // Set the request body
    //     $data = [
    //         'model' => 'text-davinci-003',
    //         'prompt' => $content,
    //         'temperature' => 0.4,
    //         'max_tokens' => 2048,
    //         'top_p' => 1,
    //         'frequency_penalty' => 0,
    //         'presence_penalty' => 0,
    //     ];




    //     $promises = [
    //         $client->postAsync($url, [
    //             'headers' => $headers,
    //             'json' => $data,
    //         ]),
    //     ];

    //     $results = Utils::unwrap($promises);
    //     $response = $results[0]->getBody()->getContents();

    //     // Get the response body
    //     $data = json_decode($response, true);
    //     $content = $data['choices'][0]['text'];




    //     $data = [

    //         'question' => 'create srs document',

    //         'answer' => $content



    //     ];

    //     $json_data = json_encode($data);
    //     gptQuestionAnswer::create([

    //         'question_and_answer' => $json_data,

    //         'user_id' => auth()->user()->id,



    //     ]);

    //     $content = str_replace('```', " ", $content);

    //     $data['choices'][0]['message']['content'] = nl2br($content);

    //     $question = [
    //         'question_name' => $data['choices'][0]['message']['content'] . '<br> can we send via mail in your registered mail ?',
    //         'id' => 'send_mail'
    //     ];

    //     return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
    // }
}
