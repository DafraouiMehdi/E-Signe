Get-ChildItem -Path Cert:\CurrentUser\My | Select-Object -Property Subject, Issuer, NotBefore, NotAfter | ConvertTo-Json