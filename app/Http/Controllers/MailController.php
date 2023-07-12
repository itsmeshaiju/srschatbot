<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

class MailController extends Controller
{
//The sendMail function is used to send the pdf file to registered mail address.
    public function sendMail($content, $mail_header, $to_mail, $subject, $type, $file_name)
    {
        try 
        {
            if ($type == 'attach') 
            {
                Mail::raw($mail_header, function ($message) use ($to_mail, $subject, $content, $file_name) {
                    $message->to($to_mail)
                        ->subject($subject)
                        ->attach($content, ['as' => $file_name]);
                });
                return 'Email sent with PDF attachment.';
            } 
            else 
            {
                Mail::raw($mail_header, function ($message) use ($to_mail, $subject, $content, $file_name) {
                    $message->to($to_mail)
                        ->subject($subject)
                        ->attach($content, ['as' => $file_name]);
                });
                return 'Email sent without PDF attachment.';
            }
        } 
        catch (Exception $e) 
        {
            // Handle the exception as per your requirements
            return 'Failed to send email: ' . $e->getMessage();
        }
    }

}


