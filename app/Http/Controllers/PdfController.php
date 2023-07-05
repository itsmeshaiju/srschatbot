<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    private function generatePDF($article)
    {
      $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
  
      // Set PDF content
      $pdf->SetMargins(15, 15, 15);
      $pdf->AddPage();
      $pdf->SetFont('helvetica', '', 12);
      $pdf->Cell(0, 10, 'Title: '.$article->title, 0, 1);
      $pdf->Cell(0, 10, 'Body: '.$article->body, 0, 1);
  
      // Output the PDF as a string
      return $pdf->Output('article.pdf', 'S');
    }
}
