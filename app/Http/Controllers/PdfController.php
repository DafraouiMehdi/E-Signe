<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;

// class PdfController extends Controller
// {
//     public function certifyPdf(Request $request)
//     {
//         try {
//             // Validate the request
//             $request->validate([
//                 'pdf' => 'required|file|mimes:pdf|max:10000',
//                 'certificate' => 'required|string', // This should be a path to the combined PEM file
//                 'namefile' => 'required|string',
//             ]);

//             // Retrieve file and certificate data
//             $pdfFilePath = $request->file('pdf')->getPathname();
//             $namefile = $request->input('namefile');
//             $pemFilePath = $request->input('certificate'); // Path to combined PEM file

//             // Log paths for debugging
//             Log::info('PEM File Path:', ['path' => $pemFilePath]);

//             // Check if the PEM file exists
//             if (!file_exists($pemFilePath)) {
//                 throw new \Exception('PEM file not found: ' . $pemFilePath);
//             }

//             // Define the output path for the signed PDF
//             $outputPath = 'C:/Users/ce pc/Laravel/esign/public/PdfSign/signed_' . $namefile;

//             // Sign the PDF using the combined PEM file
//             $this->signPdf($pdfFilePath, $pemFilePath, $outputPath);

//             // Return a success response
//             return response()->json(['message' => 'PDF signed successfully!']);

//         } catch (\Exception $e) {
//             Log::error('Error certifying PDF: ' . $e->getMessage());
//             return response()->json([
//                 'error' => 'An error occurred while certifying the PDF.',
//                 'details' => $e->getMessage(),
//             ], 500);
//         }
//     }

//     protected function signPdf($pdfFilePath, $pemFilePath, $outputPath)
//     {
//         $pemContent = file_get_contents($pemFilePath);

//         if (!$pemContent) {
//             throw new \Exception('PEM file content could not be read.');
//         }

//         $result = openssl_pkcs7_sign(
//             $pdfFilePath,
//             $outputPath,
//             $pemContent,
//             $pemContent,
//             array(),
//             PKCS7_BINARY
//         );

//         if (!$result) {
//             throw new \Exception('Error signing PDF with OpenSSL.');
//         }
//     }
// }







// namespace App\Http\Controllers;

// use Symfony\Component\Process\Process;
// use Symfony\Component\Process\Exception\ProcessFailedException;
// use Illuminate\Http\JsonResponse;
// use InvalidArgumentException;
// use Illuminate\Support\Facades\Log;

// class PdfController extends Controller
// {
//     public function getCertificates(): JsonResponse
// {
//     $scriptContent = <<<EOD
//     [Console]::OutputEncoding = [System.Text.Encoding]::UTF8

//     # Get all certificates from the user's My store
//     \$certs = Get-ChildItem -Path Cert:\\CurrentUser\\My

//     # Array to hold certificates details
//     \$certDetails = @()

//     foreach (\$cert in \$certs) {
//         \$details = @{
//             Subject = \$cert.Subject
//             Issuer = \$cert.Issuer
//             NotBefore = \$cert.NotBefore
//             NotAfter = \$cert.NotAfter
//             Thumbprint = \$cert.Thumbprint
//             EnhancedKeyUsageList = \$cert.EnhancedKeyUsageList | ForEach-Object { \$_.Oid.FriendlyName }
//             PublicKey = [System.Convert]::ToBase64String(\$cert.GetRawCertData())
//             SerialNumber = \$cert.SerialNumber
//             PrivateKey = \$null
//         }

//         try {
//             \$privateKey = \$cert.PrivateKey
//             if (\$privateKey -ne \$null) {
//                 \$pemPrivateKey = ""
//                 if (\$privateKey.GetType().Name -eq "RSACryptoServiceProvider") {
//                     \$privateKeyBlob = \$privateKey.ExportCspBlob(\$true)
//                     \$pemPrivateKey = [System.Convert]::ToBase64String(\$privateKeyBlob)
//                 } elseif (\$privateKey.GetType().Name -eq "RSACng") {
//                     \$privateKeyBlob = \$privateKey.ExportPkcs8PrivateKey()
//                     \$pemPrivateKey = [System.Convert]::ToBase64String(\$privateKeyBlob)
//                 } else {
//                     \$pemPrivateKey = "Unsupported private key type: \$(\$privateKey.GetType().Name)"
//                 }
//                 \$pemPrivateKey = "-----BEGIN PRIVATE KEY-----`n\$pemPrivateKey`n-----END PRIVATE KEY-----"
//                 \$details.PrivateKey = \$pemPrivateKey
//             } else {
//                 \$details.PrivateKey = "No private key available"
//             }
//         } catch {
//             \$details.PrivateKey = "Error accessing private key: \$_"
//         }

//         \$certDetails += \$details
//     }

//     # Convert to JSON and ensure proper encoding with BOM
//     \$utf8Json = [System.Text.Encoding]::UTF8.GetBytes(\$certDetails | ConvertTo-Json -Depth 5)
//     \$utf8JsonWithBOM = [System.Text.Encoding]::UTF8.GetString([System.Text.Encoding]::UTF8.GetPreamble() + \$utf8Json)
//     \$utf8JsonWithBOM | Out-File -Encoding utf8 -FilePath "certificates.json"
//     EOD;

//     try {
//         // Save the script content to a temporary file
//         $tempScriptFile = sys_get_temp_dir() . '/ps_CD74' . uniqid() . '.ps1';
//         file_put_contents($tempScriptFile, $scriptContent);

//         // Execute the PowerShell script
//         $process = new Process([
//             'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe',
//             '-ExecutionPolicy', 'Bypass',
//             '-File', $tempScriptFile
//         ]);
//         $process->run();

//         // Check for errors
//         if (!$process->isSuccessful()) {
//             Log::error('PowerShell process failed', ['error' => $process->getErrorOutput()]);
//             throw new ProcessFailedException($process);
//         }

//         // Read JSON output from file
//         $jsonOutputFile = sys_get_temp_dir() . '/json_output_' . uniqid() . '.json';
//         $output = file_get_contents($jsonOutputFile);
//         if ($output === false) {
//             throw new \Exception('Failed to read JSON output file.');
//         }

//         // Decode the JSON
//         $result = json_decode($output, true);
//         if (json_last_error() !== JSON_ERROR_NONE) {
//             Log::error('JSON decode error', ['error' => json_last_error_msg()]);
//             throw new InvalidArgumentException(json_last_error_msg());
//         }

//         return response()->json($result);
//     } catch (\Exception $e) {
//         Log::error('Exception in getCertificates', ['exception' => $e->getMessage()]);
//         return response()->json(['error' => $e->getMessage()], 500);
//     } finally {
//         // Clean up the temporary files
//         if (isset($tempScriptFile) && file_exists($tempScriptFile)) {
//             @unlink($tempScriptFile);
//         }
//         if (isset($jsonOutputFile) && file_exists($jsonOutputFile)) {
//             @unlink($jsonOutputFile);
//         }
//     }
// }

// }



namespace App\Http\Controllers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PdfController extends Controller
{
    public function getCertificates()
    {
        $scriptPath = public_path('ps_CD74.ps1');
        $command = "powershell -ExecutionPolicy Bypass -File \"$scriptPath\"";

        // Log the command being executed
        \Log::info("Executing command: " . $command);

        // Run the command
        $output = shell_exec($command);

        // Log the output
        \Log::info('Output: ' . $output);

        // Decode JSON output
        $data = json_decode($output, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('JSON Error: ' . json_last_error_msg());
            return response()->json(['error' => 'Failed to parse JSON output'], 500);
        }

        // Return JSON response
        return response()->json(['certificates' => $data]);
    }

}
