<?php

// app/Http/Controllers/MergeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Mpdf\Mpdf;
use Exception;

class MergeController extends Controller
{
    public function signPdf(Request $request)
    {
        try {
            return response()->json(['mess' => 'Okkkkk.']);
        } catch (Exception $e) {
            // Log the error and return a generic error message
            Log::error('Error signing PDF: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while signing the PDF.'], 500);
        }
    }
}
