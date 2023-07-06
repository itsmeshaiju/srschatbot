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
    // $content = 'Software Requirements Specification (SRS) Document
    
    //           E-commerce Application - Magento
    
    
    //           1. Introduction
    
    //              1.1 Purpose
    
    //              The purpose of this document is to provide a detailed description of the requirements for an e-commerce application based on the Magento platform.
    
    
    //              1.2 Scope
    
    //              This application aims to provide a robust and user-friendly e-commerce platform using the Magento framework. It will allow users to browse and purchase products, manage their accounts, and provide an administrative interface for managing products, orders, and customer data.
    
    
    //              1.3 Definitions, Acronyms, and Abbreviations
    
    //              - SRS: Software Requirements Specification
    
    
    //           2. Overall Description
    
    //              2.1 Product Perspective
    
    //              The e-commerce application will be built on top of the Magento platform which provides a scalable and customizable foundation for online stores. It will integrate with various payment gateways and shipping providers to facilitate secure transactions and efficient order fulfillment.
    
    
    //              2.2 Product Features
    //              Software Requirements Specification (SRS) Document
    
    //           E-commerce Application - Magento
    
    
    //           1. Introduction
    
    //              1.1 Purpose
    
    //              The purpose of this document is to provide a detailed description of the requirements for an e-commerce application based on the Magento platform.
    
    
    //              1.2 Scope
    
    //              This application aims to provide a robust and user-friendly e-commerce platform using the Magento framework. It will allow users to browse and purchase products, manage their accounts, and provide an administrative interface for managing products, orders, and customer data.
    
    
    //              1.3 Definitions, Acronyms, and Abbreviations
    
    //              - SRS: Software Requirements Specification
    
    
    //           2. Overall Description
    
    //              2.1 Product Perspective
    
    //              The e-commerce application will be built on top of the Magento platform which provides a scalable and customizable foundation for online stores. It will integrate with various payment gateways and shipping providers to facilitate secure transactions and efficient order fulfillment.
    
    
    //              2.2 Product Features
    //              Software Requirements Specification (SRS) Document
    
    //           E-commerce Application - Magento
    
    
    //           1. Introduction
    
    //              1.1 Purpose
    
    //              The purpose of this document is to provide a detailed description of the requirements for an e-commerce application based on the Magento platform.
    
    
    //              1.2 Scope
    
    //              This application aims to provide a robust and user-friendly e-commerce platform using the Magento framework. It will allow users to browse and purchase products, manage their accounts, and provide an administrative interface for managing products, orders, and customer data.
    
    
    //              1.3 Definitions, Acronyms, and Abbreviations
    
    //              - SRS: Software Requirements Specification
    
    
    //           2. Overall Description
    
    //              2.1 Product Perspective
    
    //              The e-commerce application will be built on top of the Magento platform which provides a scalable and customizable foundation for online stores. It will integrate with various payment gateways and shipping providers to facilitate secure transactions and efficient order fulfillment.
    
    
    //              2.2 Product Features';

    // Generate PDF using TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $content, 0, 'J');
    $pdfContent = public_path($pdf_name);
    $pdf->Output($pdfContent, 'F');

    return $pdfContent;
}
}
