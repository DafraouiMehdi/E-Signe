<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportPrivateKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:privatekey {pfxPath} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export private key from a PFX file and convert to DER format';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pfxPath = $this->argument('pfxPath');
        $password = $this->argument('password');

        if (!file_exists($pfxPath)) {
            $this->error("PFX file not found at path: $pfxPath");
            return 1;
        }

        $fileName = pathinfo($pfxPath, PATHINFO_FILENAME);
        $pemFilePath = storage_path("app/{$fileName}_privateKey.pem");
        $derFilePath = storage_path("app/{$fileName}_privateKey.der");

        $opensslPath = "openssl";

        $exportCommand = "{$opensslPath} pkcs12 -in {$pfxPath} -nocerts -out {$pemFilePath} -nodes -password pass:{$password}";
        $convertCommand = "{$opensslPath} rsa -in {$pemFilePath} -outform DER -out {$derFilePath}";

        exec($exportCommand, $output, $returnVar);
        if ($returnVar !== 0) {
            $this->error("Failed to export private key to PEM file");
            return 1;
        }

        exec($convertCommand, $output, $returnVar);
        if ($returnVar !== 0) {
            $this->error("Failed to convert private key to DER format");
            return 1;
        }

        $this->info("Private key successfully exported and converted to DER format");
        $this->info("PEM file path: {$pemFilePath}");
        $this->info("DER file path: {$derFilePath}");

        return 0;
    }
}
