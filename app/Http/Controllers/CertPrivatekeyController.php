<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CertPrivateKeyController extends Controller
{
    public function certify(Request $request)
    {

        // return response()->json('certify');
        // Validate incoming request
        $request->validate([
            'pdf' => 'required|mimes:pdf',
            'namefile' => 'required|string',
            'certificate' => 'required|json',
            '_token' => 'required|string',
        ]);

        // Retrieve the file and certificate from the request
        $pdfFile = $request->file('pdf');
        $namefile = $request->input('namefile');
        $certificate = json_decode($request->input('certificate'), true);

        // Example of processing the PDF and certificate
        // Here you would add your logic to certify the PDF
        // For now, let's just store the file and return a success response

        // $path = $pdfFile->storeAs('certified_pdfs', $namefile, 'public');

        // Return a success response
        return response()->json([
            'success' => true,
            'certify' => $certificate ,
        ], Response::HTTP_OK);
    }
}
