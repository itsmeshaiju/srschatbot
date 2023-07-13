<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;
//The sendMain function is used to send the pdf file to registered mail address. Here passing two variables pdfName and toMail. pdfName is the name of pdf file
class MailController extends Controller
{

    public function sendMail($attachment,$content,$to_mail,$subject,$is_attach,$file_name)
    {

        
        if ($is_attach == true) {
            Mail::raw($content, function ($message) use ($to_mail, $subject, $attachment, $file_name)
            {
            $message->to($to_mail) // Specify the recipient's email
                ->subject($subject) // Specify the email subject 
                ->attach($attachment, ['as' => $file_name]); // Specify the file path of the PDF attachment
        });
        return 'Email sent with PDF attachment.';
        }else{
            Mail::raw($content, function ($message) use ($to_mail, $subject, $attachment, $file_name)
            {
            $message->to($to_mail) // Specify the recipient's email
                ->subject($subject); // Specify the email subject 
        });
        return 'Email sent with PDF attachment.';
        }
    }


            
    
}


