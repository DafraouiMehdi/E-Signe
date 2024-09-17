<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Exception;

class CertificateController extends Controller
{
    public function listCertificates()
    {
        $os = php_uname('s'); // Get the OS name

        switch (strtolower($os)) {
            case 'windows nt':
                try {
                    $data = $this->listCertificatesWindows();
                    if (isset($data['error'])) {
                        return response()->json([
                            'error' => $data['error'],
                        ], 500);
                    }
        
                    $certificates = json_decode($data, true);

                    return view('e-sign.Degesign' , compact('certificates'));
                    // return $data;
        
                } catch (Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage(),
                    ], 500);
                }
                break;
            case 'darwin':
                try {
                    $certificates = $this->listCertificatesMac();
                    if (isset($certificates['error'])) {
                        return response()->json([
                            'error' => $certificates['error'],
                        ], 500);
                    }
        
                    return response()->json($certificates);
        
                } catch (Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage(),
                    ], 500);
                }
                break;
            case 'linux':
                try {
                    $certificates = $this->listCertificatesLinux();
                    if (isset($certificates['error'])) {
                        return response()->json([
                            'error' => $certificates['error'],
                        ], 500);
                    }
        
                    return response()->json($certificates);
        
                } catch (Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage(),
                    ], 500);
                }
                break;
            default:
                return response()->json(['error' => 'Unsupported operating system'], 400);
        }
    }

    private function listCertificatesWindows()
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
        return $output;
        // $scriptPath = 'C:/Users/ce pc/Laravel/backesign/storage/scripts/get_certificates.ps1';

        // $command = 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe -Command "[Console]::OutputEncoding = [System.Text.Encoding]::UTF8; Get-ChildItem -Path Cert:\\CurrentUser\\My | Select-Object -Property Subject, Issuer, NotBefore, NotAfter, Thumbprint, EnhancedKeyUsageList, PublicKey, SerialNumber, PrivateKey | ConvertTo-Json -Depth 5"';

        // $output = shell_exec($command);

        // if ($output === null) {
        //     return ['error' => 'Failed to execute PowerShell command'];
        // }

        // \Log::info('PowerShell output: ' . $output);

        // $decodedOutput = json_decode($output, true);
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     return ['error' => 'Failed to decode JSON output: ' . json_last_error_msg()];
        // }

        // return $decodedOutput;
    }

    private function listCertificatesMac()
    {
        $securityPath = $this->findExecutable('security');
        if (!$securityPath) {
            return ['error' => 'Security command not found'];
        }

        $command = "$securityPath find-certificate -a -p";
        $process = new Process(explode(' ', $command));
        $process->run();

        if (!$process->isSuccessful()) {
            return ['error' => 'Failed to execute security command'];
        }

        return ['certificates' => $this->parseMacCertificates($process->getOutput())];
    }

    private function listCertificatesLinux()
    {
        $opensslPath = $this->findExecutable('openssl');
        if (!$opensslPath) {
            return ['error' => 'OpenSSL command not found'];
        }

        $command = "find /etc/ssl/certs -name \"*.pem\" -exec $opensslPath x509 -in {} -text -noout \\;";
        $process = new Process(explode(' ', $command));
        $process->run();

        if (!$process->isSuccessful()) {
            return ['error' => 'Failed to execute OpenSSL command'];
        }

        return ['certificates' => $process->getOutput()];
    }

    private function findExecutable($executable)
    {
        $process = new Process(['which', $executable]);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $paths = explode(PHP_EOL, $process->getOutput());
        return trim($paths[0]);
    }

    private function parseMacCertificates($output)
    {
        // Implement parsing logic if needed
        return $output;
    }
}
