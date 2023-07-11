<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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

}

