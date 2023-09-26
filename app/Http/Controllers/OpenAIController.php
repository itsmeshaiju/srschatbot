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
use Exception;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\MailController;
use App\Models\MasterQuestion;
use App\Models\SubQuestion;
use Dompdf\Options;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    public function index(Request $request) // show chat window 
    {
        return view('endUser.chatWindow'); //return html file
    }



    

    public function getQuestions(Request $request)
    {
        try {
            // Check if the request is for sending an email
            if (isset($request->q_id) && $request->q_id == 'send_mail') {
                // Generate a unique PDF name
                $pdfName = "SRSDocument_" . date("ymdhis") . Auth::user()->id . ".pdf";
    
                // Create an instance of the PdfController
                $pdfController = new PdfController();
    
                // Generate the PDF and get the attachment
                $attachment = $pdfController->generatePDF($pdfName);
    
                // Create an instance of the MailController
                $mailController = new MailController();
    
                // Compose email content
                $content = 'Hi ' . Auth::user()->name . ', Please find the attached SRS Document along with the mail. Have a nice day!';
                $toMail = Auth::user()->email;
                $subject = 'SRS Document';
                $isAttach = true;
    
                // Send the email with the attachment
                $mailController->sendMail($attachment, $content, $toMail, $subject, $isAttach, $pdfName);
    
                // Delete all question answer data after sending the email
                gptQuestionAnswer::where('user_id', auth()->user()->id)->delete();
    
                // Prepare a response for the last step
                $question = [
                    'question' => 'We will share your PDF shortly. Have a nice day!',
                    'answer' => '',
                    'is_repeat' => 0,
                    'next_question' => "",
                    'is_last_question' => 0,
                    'options_html' => "",
                    'id' => 0
                ];
    
                // Return the response as JSON
                return response()->json($question, 200, [], JSON_PRETTY_PRINT);
            }
    
            // Check if user_answer and chat_question are provided in the request
            if (!empty($request->user_answer) && !empty($request->chat_question)) {
                $data = [
                    'answer' => $request->user_answer,
                    'question' => $request->chat_question,
                ];
    
                // Convert data to JSON
                $json_data = json_encode($data);
    
                // Insert question and answer data into the database
                gptQuestionAnswer::create([
                    'question_and_answer' => $json_data,
                    'options_html' => '',
                    'user_id' => auth()->user()->id,
                ]);
            }
    
            // Determine the question based on the request
            $id = isset($request->q_id) ? $request->q_id : 0;
    
            if ($id == 0) {
                // Fetch the first master question
                $masterQuestion = MasterQuestion::where('is_first_question', 1)->first();
                $masterQuestion->is_repeat = 0;
                $subQuestions = $masterQuestion->subQuestion;
            } else {
                // Fetch a subquestion by ID
                $masterQuestion = SubQuestion::find($id);
                $subQuestions = $masterQuestion->subQuestionList($masterQuestion->level_id, $masterQuestion->id);
            }
    
            // Handle repeated or last questions
            if ($masterQuestion->is_repeat == 1) {
                return $this->repeatQuestion($id, $masterQuestion);
            } elseif ($masterQuestion->is_repeat == 0 && count($subQuestions) == 0) {
                return $this->lastQuestion($masterQuestion);
            }
    
            // Generate options HTML
            $options = $this->optionsHtml($id, $masterQuestion->question, $subQuestions);
    
            // Prepare the question response
            $question = [
                'question' => $masterQuestion->question,
                'answer' => isset($masterQuestion->answer) ? $masterQuestion->answer : '',
                'is_repeat' => $masterQuestion->is_repeat,
                'next_question' => "",
                'is_last_question' => 0,
                'options_html' => $options,
                'id' => 0
            ];
    
            // Return the question response as JSON
            return response()->json($question, 200, [], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Handle exceptions here, log them, or return an error response
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
    







    
    //this function for call chatgpt api and return response 
    
    public function chatGpt()
    {
        try {
            // Retrieve all the user's asked questions and answers
            $qtArray = gptQuestionAnswer::select('question_and_answer')
                ->where('user_id', auth()->user()->id)
                ->orderBy('id')
                ->get();
    
            // Check if there are any questions and answers
            if (empty($qtArray)) {
                throw new Exception("Questions and answers are empty");
            }
    
            $content = "";
            foreach ($qtArray as $data) {
                $data = json_decode($data->question_and_answer, TRUE);
                $content .= $data['answer'] . ' ' . $data['question'];
            }
    
            // Add text for SRS document creation
            $content .= '  create a detailed description of the SRS document';
    
            // Initialize a Guzzle HTTP client with SSL certificate fix
            $client = new Client([
                'curl' => [
                    CURLOPT_CAINFO => base_path('resources/assets/cacert.pem')  // Fix SSL certificate problem
                ]
            ]);
    
            // Send a POST request to the OpenAI API
            $response = $client->post("https://api.openai.com/v1/chat/completions", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                ],
                'json' => [
                    "model" => "gpt-3.5-turbo",  // Chat GPT model engine
                    'messages' => [
                        [
                            "role" => "user", // Set role as user
                            "content" => $content   // Pass question and answer string
                        ]
                    ],
                    'temperature' => 0.5, // Control the randomness of generated text
                    'max_tokens' => 2048, // Set the maximum number of tokens in the response
                    'top_p' => 1, // Control text diversity
                    'frequency_penalty' => 0, // Discourage repetitive responses
                    'presence_penalty' => 0, // Discourage the model from making things up
                ]
            ]);
    
            // Parse the API response
            $data = json_decode($response->getBody(), true);
    
            // Extract the response text
            $content = $data['choices'][0]['message']['content'];
    
            // Check if the response is empty
            if (strlen($content) < 5) {
                throw new Exception("Response data is empty");
            }
    
            // Prepare the GPT answer
            $data = [
                'question' => 'create SRS document',
                'answer' => $content
            ];
    
            // Convert data to JSON
            $json_data = json_encode($data);
    
            // Save the GPT response to the table
            $res = gptQuestionAnswer::create([
                'question_and_answer' => $json_data,
                'user_id' => auth()->user()->id,
            ]);
    
            // Check if the response was saved successfully
            if (!isset($res->id)) {
                throw new Exception("Server error");
            }
    
            // Replace unwanted strings
            $content = str_replace('```', " ", $content);
    
            // Add <br> tags for line breaks
            $data['choices'][0]['message']['content'] = nl2br($content);
    
            // Generate a response for sending an email
            $res = [
                'gpt_report' => $data['choices'][0]['message']['content'] . '<br>  Shall we send this SRS document to your registered email ?',
            ];
    
            // Return the response
            return $res;
        } catch (Exception $e) {
            // Handle exceptions here, log them, or return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }








    //fetch last question
    public function lastQuestion($masterQuestion)
{
    // Get the GPT-generated answer
    $gpt_answer = $this->chatGpt();
   // dd($gpt_answer);
    // Fetch the last question from the database
    $lastQuestion = MasterQuestion::select('*')->where('is_last_question', 1)->first();

    // Prepare the response
    $gpt_res['question'] = $gpt_answer['gpt_report'];
    $question = [
        'question' => $lastQuestion->question,
        'answer' => '',
        'is_repeat' => 1,
        'is_last_question' => 1,
        'next_question' => $gpt_res,
        'options_html' => "",
        'next_options_html' => '',
        'id' => 'send_mail'
    ];

    // Return the response as JSON
    return response()->json($question, 200, [], JSON_PRETTY_PRINT);
}







//append options button
public function optionsHtml($id, $masterQuestion, $subQuestions)
{
    $options = '<div class="row col-md-12 d-flex justify-content-center">';
    $i = 0;

    foreach ($subQuestions as $q) {
        $inputId = '#btn_row_' . $i . '_' . $id;
        $htmlId = 'btn_row_' . $i . '_' . $id;
        $options .= '<div class="col-md-3 ml-3 mb-2 mt-2 text-center">';
        $options .= '<button class="btn-sm btn btn-outline-primary" id="' . $htmlId . '" ';
        $options .= 'onclick="getButtonText( \'' . $q->id . '\',\'' . $masterQuestion . '\',\'' . $q->question . '\',\'' . $inputId . '\')">';
        $options .= ucwords($q->question);
        $options .= '</button></div>';
        $i++;
    }

    $options .= '</div>';
    return $options;
}






//fetch  repeat question
public function repeatQuestion($id, $masterQuestion)
{
    // Fetch the next master question based on the current master question's relationship
    $nextMasterQuestion = SubQuestion::select('*')->where('id', $masterQuestion->master_id)->first();

    // Retrieve subquestions related to the next master question
    $subQuestions = $nextMasterQuestion->subQuestionList($nextMasterQuestion->level_id, $nextMasterQuestion->id);

    // Generate HTML options for subquestions
    $options = $this->optionsHtml($id, $masterQuestion->question, $subQuestions);

    // Prepare the response
    $question = [
        'question' => $masterQuestion->question,
        'answer' => (isset($masterQuestion->answer) ? $masterQuestion->answer : ''),
        'is_repeat' => $masterQuestion->is_repeat,
        'is_last_question' => 0,
        'next_question' => $nextMasterQuestion,
        'options_html' => "",
        'next_options_html' => $options,
        'id' => 0
    ];

    // Return the response as JSON
    return response()->json($question, 200, [], JSON_PRETTY_PRINT);
}

}
