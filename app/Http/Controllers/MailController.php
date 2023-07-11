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

    public function sendMail($content,$mail_header,$to_mail,$subject,$type,$file_name)
    {

        
        if ($type == 'attach') {
            Mail::raw($mail_header, function ($message) use ($to_mail, $subject, $content, $file_name)
            {
            $message->to($to_mail) // Specify the recipient's email
                ->subject($subject) // Specify the email subject 
                ->attach($content, ['as' => $file_name]); // Specify the file path of the PDF attachment
        });
        return 'Email sent with PDF attachment.';
        }else{
            Mail::raw($mail_header, function ($message) use ($to_mail, $subject, $content, $file_name)
            {
            $message->to($to_mail) // Specify the recipient's email
                ->subject($subject) // Specify the email subject 
                ->attach($content, ['as' => $file_name]); // Specify the file path of the PDF attachment
        });
        return 'Email sent with PDF attachment.';
        }
    }


            // Return an error message or throw the exception if needed
            return 'Failed to send email. Please try again later.';
        }
    }
}


