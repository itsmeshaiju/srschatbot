<?php


namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use Illuminate\Mail\Message;
use App\Http\Controllers\PdfController;

class MailController extends Controller
{

    public function sendMail($data,$pdf_name)
{
    

    $recipient = 'sinfolitz@gmail.com'; // Set the recipient email address
    $subject = 'SRS Document'; // Set the email subject
    $filePath = $pdf_name; // Set the filename for the PDF attachment
    // $pdfContent = 'Please find the attached SRS PDF.'; // Replace this with the actual content of the PDF


    Mail::raw('Please find the attached SRS PDF.', function ($message) use ($recipient, $subject, $filePath, $pdf_name) {
       
        $message->to($recipient) // Use the $recipient variable instead of 'recipient'
            ->subject($subject) // Use the $subject variable instead of 'subject'
            ->attach($filePath, ['as' => $pdf_name]); // Use the $fileName variable instead of $content['fileName']
    });
    

    return 'Email sent with PDF attachment.';
}


}

