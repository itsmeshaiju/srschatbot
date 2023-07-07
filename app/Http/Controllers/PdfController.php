<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models;
use TCPDF;
// use Dompdf\Dompdf;
// use App\Http\Controllers\OpenAIController;
use App\Models\gptQuestionAnswer;
class PdfController extends Controller
{
    public function generatePDF($pdf_name)
{
    $data = gptQuestionAnswer::select('id', 'question_and_answer')->where('user_id',auth()->user()->id)->orderBy('id','desc')->first();
    $data = json_decode($data['question_and_answer'], TRUE);
    $content = $data['answer'];
   
    // Generate PDF using TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, 'Content: '.$content, 0, 1);
    $pdfContent = public_path($pdf_name);
    $pdf->Output($pdfContent, 'F');

    
    return $pdfContent;
}


}
