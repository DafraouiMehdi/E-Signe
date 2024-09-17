<?php

$certPath = 'C:\path\to\your\certificate.pem';  // Update this path
$certContent = file_get_contents($certPath);

if ($certContent === false) {
    die('Error reading certificate file.');
}

$cert = openssl_x509_parse($certContent);

if ($cert === false) {
    die('Error parsing certificate.');
}

echo '<pre>';
print_r($cert);
echo '</pre>';
