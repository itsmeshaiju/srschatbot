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
    $filePath = $pdfName; // Set the filename for the PDF attachment
    // $pdfContent = 'Please find the attached SRS PDF.'; // Replace this with the actual content of the PDF


    Mail::raw('Hai ' . Auth::user()->name . ', Please find the attached SRS Document along with the mail. Have a nice day <span style="font-size: 24px; font-weight: bold;">!</span>' , function ($message) use ($recipient, $subject, $filePath, $pdfName) {
        $message->to($recipient) // Use the $recipient variable instead of 'recipient'
            ->subject($subject) // Use the $subject variable instead of 'subject'
            ->attach($filePath, ['as' => $pdfName]); // Use the $fileName variable instead of $content['fileName']
    });
    

    return 'Email sent with PDF attachment.';
}


}

