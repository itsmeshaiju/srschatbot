<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use Illuminate\Mail\Message;
use App\Http\Controllers\PdfController;

class MailController extends Controller
{

    public function sendMail($data)
{
    
    $content = $data['choices'][0]['message']['content'];

    $recipient = 'sinfolitz@gmail.com'; // Set the recipient email address
    $subject = 'Bot Data'; // Set the email subject
    $fileName = 'bot_data.pdf'; // Set the filename for the PDF attachment
    // $pdfContent = 'Please find the attached SRS PDF.'; // Replace this with the actual content of the PDF

    // Generate the PDF with filled data
    $pdfController = new PdfController();
    $pdfContent = $pdfController->generatePDF($data);

    Mail::raw('Please find attached the Bot Data PDF.', function ($message) use ($content, $recipient, $subject, $fileName, $pdfContent) {
        $message->to($recipient) // Use the $recipient variable instead of 'recipient'
            ->subject($subject) // Use the $subject variable instead of 'subject'
            ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']); // Use the $fileName variable instead of $content['fileName']
    });
    

    return 'Email sent with PDF attachment.';
}


}

