<!DOCTYPE html>
<html>
<head>
    <title>Certificates</title>
</head>
<body>
    <h1>Certificates</h1>
    @foreach($certificates as $certificate)
        <div>
            <p><strong>Subject:</strong> {{ is_array($certificate['Subject']) ? implode(', ', $certificate['Subject']) : $certificate['Subject'] ?? 'N/A' }}</p>
            <p><strong>Issuer:</strong> {{ is_array($certificate['Issuer']) ? implode(', ', $certificate['Issuer']) : $certificate['Issuer'] ?? 'N/A' }}</p>
            <p><strong>NotBefore:</strong> {{ isset($certificate['NotBefore']) ? date('Y-m-d H:i:s', strtotime($certificate['NotBefore'])) : 'N/A' }}</p>
            <p><strong>NotAfter:</strong> {{ isset($certificate['NotAfter']) ? date('Y-m-d H:i:s', strtotime($certificate['NotAfter'])) : 'N/A' }}</p>
            <p><strong>Thumbprint:</strong> {{ $certificate['Thumbprint'] ?? 'N/A' }}</p>
            <p><strong>EnhancedKeyUsageList:</strong> 
                @if(isset($certificate['EnhancedKeyUsageList']) && is_array($certificate['EnhancedKeyUsageList']))
                    @foreach($certificate['EnhancedKeyUsageList'] as $usage)
                        {{ $usage['ObjectId']['FriendlyName'] ?? 'N/A' }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    N/A
                @endif
            </p>
            <p><strong>PublicKey:</strong> 
                {{ is_array($certificate['PublicKey']) ? json_encode($certificate['PublicKey']) : ($certificate['PublicKey'] ?? 'N/A') }}
            </p>

            <p><strong>PrivateKey:</strong> 
                @if (is_array($certificate['PrivateKey']))
                    {{ json_encode($certificate['PrivateKey']) }}
                @else
                    {{ $certificate['PrivateKey'] ?? 'N/A' }}
                @endif
            </p>




            <p><strong>SerialNumber:</strong> {{ $certificate['SerialNumber'] ?? 'N/A' }}</p>
        </div>
        <hr>
    @endforeach
</body>
</html>
