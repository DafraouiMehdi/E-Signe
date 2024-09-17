<?php

$certificates = [];
try {
    // Create a COM object for the CAPICOM.Store class
    $store = new COM('CAPICOM.Store');

    // Open the certificate store for the current user
    $store->Open(1); // 1 = CAPICOM_CURRENT_USER_STORE

    // Access the collection of certificates
    $certs = $store->Certificates;

    // Loop through each certificate in the store
    foreach ($certs as $cert) {
        $certificates[] = [
            'subject' => $cert->SubjectName->Name, // Subject of the certificate
            'issuer' => $cert->IssuerName->Name,   // Issuer of the certificate
            'validFrom' => $cert->ValidFromDate,    // Validity start date
            'validTo' => $cert->ValidToDate,        // Validity end date
        ];
    }

    // Close the certificate store
    $store->Close();
} catch (Exception $e) {
    // Handle any errors that occur
    echo 'Error: ',  $e->getMessage(), "\n";
}

// Print the certificates array in a readable format
echo '<pre>';
print_r($certificates);
echo '</pre>';
