<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models;
use TCPDF;
// use Dompdf\Dompdf;
// use App\Http\Controllers\OpenAIController;

class PdfController extends Controller
{
    public function generatePDF($data, $pdf_name)
{
    $content = $data['choices'][0]['message']['content'];

    // Generate PDF using TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Content: '.$content, 0, 1);
    $pdfContent = $pdf->Output($pdf_name,'S');

    
    return $pdfContent;
}


}
