<?php




namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\JsonResponse;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Auth;

use App\Models\gptQuestionAnswer;
use App\Models\Question;

$data_list = [];

use Illuminate\Support\Facades\Mail;

use Illuminate\Mail\Message;

use Dompdf\Dompdf;

use App\Mail\BotDataMail;

use PhpOffice\PhpWord\IOFactory;

use App\Http\Controllers\PdfController;

use App\Http\Controllers\MailController;

class OpenAIController extends Controller

{
    /**

     * Write code on Method

     *

     * @return response(request )

     */

    public function index(Request $request)
    {

        return view('chatWindow');
    }



    public function getQuestions(Request $request)
    {


        $qt_count = Question::count();

        if ($request->qt_count == $qt_count) {

            $question = [

                'question_name' => 'can we procceed ?',
                'id' => 0
            ];

            return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
        }
        if (isset($request->q_id) && $request->q_id == 0) {

            $bot_data   =  $this->botData();
            return $bot_data;
        }
        if (isset($request->q_id) && $request->q_id == 'send_mail') {


            $name = "SRSDocument_" . date("ymdhis") . '.pdf';

            $pdfController = new PdfController();
            $pdfContent = $pdfController->generatePDF($name);

            $mailController = new MailController();
            $mailController->sendMail($pdfContent);
        }

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

    public function botData(): JsonResponse
    {

        $qt_array = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get();


        $contant = "";


        foreach ($qt_array as $data) {
            $data = json_decode($data->question_and_answer, TRUE);

            $contant .= $data['question'] . ' ' . $data['answer'];
        }

        $contant .= '  create srs document';

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
                        "content" => $contant

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

            'question' => 'create srs document',

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

        $question = [
            'question_name' => $data['choices'][0]['message']['content'] . '<br> can we send via mail in your registered mail ?',
            'id' => 'send_mail'
        ];

        return response()->json($question, 200, array(), JSON_PRETTY_PRINT);
    }
}