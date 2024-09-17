try {
    [Console]::OutputEncoding = [System.Text.Encoding]::UTF8

    $certs = Get-ChildItem -Path Cert:\CurrentUser\My

    $certDetails = @()

    foreach ($cert in $certs) {
        $details = @{
            Subject = $cert.Subject
            Issuer = $cert.Issuer
            NotBefore = $cert.NotBefore
            NotAfter = $cert.NotAfter
            Thumbprint = $cert.Thumbprint
            EnhancedKeyUsageList = $cert.EnhancedKeyUsageList | ForEach-Object { $_.Oid.FriendlyName }
            PublicKey = [System.Convert]::ToBase64String($cert.GetRawCertData())
            SerialNumber = $cert.SerialNumber
            PrivateKey = $null
        }

        try {
            $privateKey = $cert.PrivateKey
            if ($privateKey -ne $null) {
                $pemPrivateKey = ""
                if ($privateKey.GetType().Name -eq "RSACryptoServiceProvider") {
                    $privateKeyBlob = $privateKey.ExportCspBlob($true)
                    $pemPrivateKey = [System.Convert]::ToBase64String($privateKeyBlob)
                } elseif ($privateKey.GetType().Name -eq "RSACng") {
                    $privateKeyBlob = $privateKey.ExportPkcs8PrivateKey()
                    $pemPrivateKey = [System.Convert]::ToBase64String($privateKeyBlob)
                } else {
                    $pemPrivateKey = "Unsupported private key type: $($privateKey.GetType().Name)"
                }
                $pemPrivateKey = "-----BEGIN PRIVATE KEY-----`n$pemPrivateKey`n-----END PRIVATE KEY-----"
                $details.PrivateKey = $pemPrivateKey
            } else {
                $details.PrivateKey = "No private key available"
            }
        } catch {
            $details.PrivateKey = "Error accessing private key: $_"
        }

        $certDetails += $details
    }

    $certDetails | ConvertTo-Json -Depth 5 | Out-String | Write-Output
} catch {
    Write-Error "An error occurred: $_"
}