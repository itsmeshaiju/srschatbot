<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use TCPDF;
use App\Http\Controllers\OpenAIController;

class PdfController extends Controller
{
    public function generatePDF($questions)
    {
      $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
  
      // Set PDF content
      $pdf->SetMargins(15, 15, 15);
      $pdf->AddPage();
      $pdf->SetFont('helvetica', '', 12);
      $pdf->Cell(0, 10, 'Message: '.$questions->question_name, 0, 1);
      $pdf->Cell(0, 10, 'Content: '.$questions->content, 0, 1);
  
      // Output the PDF as a string
      return $pdf->Output('questions.pdf', 'S');
    }
}
