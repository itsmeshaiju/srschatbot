<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
class MailController extends Controller
{

    public function sendMail($pdfName, $toMail = false)
    {
        $recipient = auth::user()->email; // Set the recipient email address
        $subject = 'SRS Document'; // Set the email subject
        $filePath = $pdfName; // Set the filepath for the PDF attachment

        $mailMessage = 'Hi ' . Auth::user()->name . ', Please find the attached SRS Document along with the mail. Have a nice day!';

        Mail::raw($mailMessage, function ($message) use ($recipient, $subject, $filePath, $pdfName)
            {
            $message->to($recipient) // Specify the recipient's email
                ->subject($subject) // Specify the email subject 
                ->attach($filePath, ['as' => $pdfName]); // Specify the file path of the PDF attachment
        });
        return 'Email sent with PDF attachment.';
    }

}

