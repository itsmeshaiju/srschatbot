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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    public function index(Request $request) // show chat window 
    {
        return view('chatWindow'); //return html file
    }

    public function getQuestions(Request $request) //ajax request for getting questions
    {
        try {
            
            $qtCount = Question::count(); //get all question count 
            if (empty($qtCount)) { 
                throw new Exception("Questions are empty");
            }
            if ($request->qt_count == $qtCount) {  //checking this is last question or not
                $question = [
                    'question_name' => 'Can we procceed ?',
                    'options_html'=>'',
                    'id' => 0
                ]; // create response for last question befor generating srs document
                return response()->json($question, 200, array(), JSON_PRETTY_PRINT); //return json data
            }
            /*
        This block for send content to chatgpt
        checking for all questions are completed  or not if completed all questions and answers  are push to  chat gpt api 
        */
            if (isset($request->q_id) && $request->q_id == 0) {
                $chatGpt   =  $this->chatGpt();  // call chatgpt api function 
                return $chatGpt; //return chat gpt generated data
            }

            /*
        This block for srs document convert to pdf and send via mail
        push data to pdf generating   then send via  registerd  mail 
        */
            if (isset($request->q_id) && $request->q_id == 'send_mail') { //checking next request is send mail or not
                $text =  date("ymdhis") . Auth::user()->id; //generated pdf name make unique
                // $text = Hash::make($text); //text hashing or text  convert to a  specific code 
                $name = "SRSDocument_" . $text . ".pdf"; //pdf name 
                $pdfController = new PdfController(); //call pdf controller 
                $attachment = $pdfController->generatePDF($name); //call pdf controller function and get return data
                $mailController = new MailController(); // call mail controller 
                $content = 'Hi ' . Auth::user()->name . ', Please find the attached SRS Document along with the mail. Have a nice day!';
                $to_mail = auth::user()->email;
                $subject = 'SRS Document';
                $is_attach = true;
                $mailController->sendMail($attachment, $content, $to_mail, $subject, $is_attach, $name); //call mail controller function and get return data
                gptQuestionAnswer::where('user_id', auth()->user()->id)->delete(); //delete all question answer data after send mail 
                $question = [
                    'question_name' => 'we will share you pdf shortly..have a nice day',
                    'options_html'=>'',
                    'id' => 'shared_mail'
                ]; // last reponse for after send registerd mail
                return response()->json($question, 200, array(), JSON_PRETTY_PRINT); //return json data
            }
            /* 
        this block for get next questions based on  id order
        */
            $id = (isset($request->q_id) ? $request->q_id  : 0); //check id exists or not if id is null set value 0 then get first row 
            $question = Question::select('id', 'question_name','options')->where('id', '>', $id)->orderBy('id')->first(); // getting next asking row
            $options = "";
           
            if (isset($question->options) && $question->options != ''){

                $array = explode(",", $question->options);
                $options .='<div class="row col-md-12">';
                foreach($array as $q){
                    $options .='<div class="col-md-3"><button class="btn btn-success " onclick="getButtonText(\''.$q.'\')">'.ucwords($q).'</button></div>';
                }
                $options .= '</div>';
               
            }
            $data = [
                'answer' => $request->user_input,
                'question' => $question->question_name
                
            ]; // set ask question and answer to array 
            $json_data = json_encode($data); // convert to json 
            $res =  gptQuestionAnswer::create([
                'question_and_answer' => $json_data,
                'options_html'=>'',
                'user_id' => auth()->user()->id,
            ]); // insert to json data and logged user id  to table 
            if(isset($res->id) == false){
                throw new Exception("server error ");
            }
            $question['options_html'] = $options;
            return response()->json($question, 200, array(), JSON_PRETTY_PRINT); //return next question in  json format
        } catch (\Exception $e) {
            Log::channel('openai')->error($e);
            $question['message'] = "something went wrong try again later";
            return response()->json($question, 500, array(), JSON_PRETTY_PRINT); //return next question in  json format
        }
    }


    /*
    this function for call chatgpt api and return response 
    */
    public function chatGpt(): JsonResponse
    {
        $qtArray = gptQuestionAnswer::select('question_and_answer')->where('user_id', auth()->user()->id)->orderBy('id')->get(); //get all logged  user  asked questions and answers
        if (empty($qtArray)) { 
            throw new Exception("Questions and answers  are empty");
        }
        $content = "";
        foreach ($qtArray as $data) {
            $data = json_decode($data->question_and_answer, TRUE);
            $content .= $data['answer'] . ' ' . $data['question'];
        } // all questions and answers  convert to string 
        $content .= '  create srs document'; // add text on for srs document creation 
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
            ], //set api header issue ' env('OPENAI_API_KEY')' this data will get .env file 

            'json' => [
                "model" => "gpt-3.5-turbo",  //chat gpt model engine
                'messages' => [
                    [
                        "role" => "user", //set role as user
                        "content" => $content   //pass question and asnwer string is there 
                    ]
                ],
                'temperature' => 0.5, //It controls the randomness of the generated text  0.5 makes the output more random and creative
                'max_tokens' => 2048, // It sets the maximum number of tokens in the response. 
                'top_p' => 1, //This parameter controls the diversity of the generated text by limiting the set of tokens considered for each step
                'frequency_penalty' => 0, // discourage repetitive responses
                'presence_penalty' => 0, //discourage the model from making stuff up
            ]
        ]);
        $data = json_decode($response->getBody(), true); //convert response to array
        $content = $data['choices'][0]['message']['content']; //take response text only
        if (strlen($content) < 5){
            throw new Exception("response data is empty");
        }
        
        $data = [
            'question' => 'create srs document',
            'answer' => $content
        ]; // Prepare gpt answer to array 

        $json_data = json_encode($data); // convert to json
        $res = gptQuestionAnswer::create([
            'question_and_answer' => $json_data,
            'user_id' => auth()->user()->id,
        ]); //save gpt response to table 
        if(isset($res->id) == false){
            throw new Exception("server error ");
        }
        $content = str_replace('```', " ", $content); // replace unwanted stings
        $data['choices'][0]['message']['content'] = nl2br($content); // add <br> tag
        $question = [
            'question_name' => $data['choices'][0]['message']['content'] . '<br>  Shall we send this SRS document to your registered email ?',
            'options_html'=>'',
            'id' => 'send_mail'
        ]; // generate response for send mail 
        return response()->json($question, 200, array(), JSON_PRETTY_PRINT); // return json data to view 
    }
}
