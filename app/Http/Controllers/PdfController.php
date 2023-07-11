<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\gptQuestionAnswer;
class PdfController extends Controller
{
    public function generatePDF($pdfName) //The generatePDF function takes a parameter $pdfName (the name of the PDF file)
    {
        $data = gptQuestionAnswer::select('id', 'question_and_answer')->where('user_id',auth()->user()->id)->orderBy('id','desc')->first(); //It retrieves the necessary data from the gptQuestionAnswer table using the select, where, and orderBy methods. 
        $data = json_decode($data['question_and_answer'], TRUE); //The json data is converted to array.
        $content = $data['answer']; //The $content variable stores the answer value from the array index data.
    
        // Generate PDF using TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); // Set page orientation, unit of measurement, and page size
        $pdf->SetMargins(15, 15, 15); //Set margins
        $pdf->AddPage(); // Add new page
        $pdf->SetFont('helvetica', '', 12); //The font is set to 'Helvetica' with a size of 12
        $pdf->MultiCell(0, 10, 'Content: '.$content, 0, 1); //Multicell method is used to add the content to the pdf and displayed as a multi-line cell
        $pdfContent = public_path('SRSDoc/' . $pdfName); //The $pdfContent variable is assigned the path where the PDF file will be saved.
        $pdf->Output($pdfContent, 'F'); //used to save the PDF file to the specified path
        return $pdfContent;
    }

}
