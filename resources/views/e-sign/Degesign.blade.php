<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Signature Electronique</title>
    <style>
        /* Style existing buttons */
        #btn, #certifyBtn {
            background-color: green;
            padding: 10px 20px;
            color: white;
            border: 1px solid green;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #btn:hover, #certifyBtn:hover {
            background-color: darkgreen;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        #btn:active, #certifyBtn:active {
            background-color: forestgreen;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filePreview {
            width: 300px;
            height: 400px;
            border: 2px solid #ccc;
            overflow: hidden;
            position: relative;
            margin-left: 5%;
            margin-top: 10%;
            margin-bottom: 10%;
        }
        canvas {
            display: block;
            width: 100%;
            height: 100% !important;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 1% auto;
            padding: 20px;
            border: 1px solid #888;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            width: 50%;
            max-width: 900px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        .filePreview {
            margin-right: 20px;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }
        form {
            margin-top: 50%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        section {
            margin-top: 10px;
        }
        .draggable {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            padding: 10px;
            cursor: move;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .certificate-info {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #007bff;
            padding-left: 25px;
            margin-top: -65px;
            cursor: move;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: auto;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }
        .certificate-info-content {
            display: flex;
            align-items: center;
        }
        .qr-code {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }
        .certificate-info-text {
            display: flex;
            flex-direction: column;
        }
        .certificate-info p {
            margin: 0;
            font-size: 12px;
        }
        .btn-download {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-download:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-download:active {
            background-color: #004494;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-download:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        @media (max-width: 768px) {
            .modal-content {
                flex-direction: column;
                width: 90%;
            }
            .filePreview {
                width: 100%;
                height: auto;
                margin-right: 0;
            }
            .form-container {
                margin-left: 0;
                margin-bottom: 10%;
            }
        }
    </style>
</head>
<body>
    <button id="btn">Upload PDF</button>
    <input type="file" id="fileInput" accept="application/pdf" style="display: none;">

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="certifyForm">
            <div id="filePreview" class="filePreview"></div>
            <div class="form-container">
                <form id="certifyForm">
                    <label for="certificateSelect">Select Certificate:</label>
                    <select id="certificateSelect">
                        
                    </select>
                    <button type="button" id="certifyBtn" class="btn-download">Certify PDF</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const certificates = @json($certificates); 
            const fileInput = document.getElementById('fileInput');
            const uploadBtn = document.getElementById('btn');
            const filePreview = document.getElementById('filePreview');
            const modal = document.getElementById('myModal');
            const closeModalBtn = document.getElementsByClassName('close')[0];
            const selectElement = document.getElementById('certificateSelect');
            const certifyBtn = document.getElementById('certifyBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let pdfData = null;
            let namefile = null;

            // Populate certificate select options
            const addedNames = {};
            certificates.forEach(certificate => {
                if (!addedNames[certificate.Subject]) {
                    const option = document.createElement('option');
                    option.value = JSON.stringify(certificate);
                    option.textContent = certificate.Subject;
                    selectElement.appendChild(option);
                    addedNames[certificate.Subject] = true;
                }
            });

            // Open file input dialog
            uploadBtn.addEventListener('click', function() {
                fileInput.click();
            });

            // Handle file input change
            fileInput.addEventListener('change', function() {
                const file = fileInput.files[0];
                if (file) {
                    namefile = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        pdfData = new Uint8Array(e.target.result);
                        renderPdf(pdfData);
                        modal.style.display = 'block';
                    };
                    reader.readAsArrayBuffer(file);
                }
            });

            // Render the first page of the PDF
            function renderPdf(pdfData) {
                pdfjsLib.getDocument({ data: pdfData }).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function() {
                            filePreview.innerHTML = '';
                            filePreview.appendChild(canvas);
                        }).catch(function(error) {
                            console.error('Error rendering PDF page:', error);
                        });
                    }).catch(function(error) {
                        console.error('Error getting PDF page:', error);
                    });
                }).catch(function(error) {
                    console.error('Error loading PDF document:', error);
                });
            }

            // Close modal
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // Handle certify button click
            
            // Handle certify button click
            certifyBtn.addEventListener('click', function() {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const certificate = JSON.parse(selectedOption.value);

                if (!pdfData || !certificate) {
                    alert('Please upload a PDF file and select a certificate.');
                    return;
                }

                const formData = new FormData();
                formData.append('pdf', new Blob([pdfData], { type: 'application/pdf' }), namefile);
                formData.append('certificate', JSON.stringify(certificate));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);
                console.log(certificate);
                

                // Perform the fetch request
                fetch('{{ url('signPdf') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.blob(); // Ensure the response is treated as a Blob
                })
                .then(blob => {
                    alert('PDF successfully signed!');
                    console.log(blob);
                })
                .catch(error => {
                    console.error('Error signing PDF:', error);
                });
            });
        });
    </script>
</body>
</html> -->




<!-- ############################################### -->




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Signature Electronique</title>
    <style>
        /* Style existing buttons */
        #btn, #certifyBtn {
            background-color: green;
            padding: 10px 20px;
            color: white;
            border: 1px solid green;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #btn:hover, #certifyBtn:hover {
            background-color: darkgreen;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        #btn:active, #certifyBtn:active {
            background-color: forestgreen;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filePreview {
            width: 300px;
            height: 400px;
            border: 2px solid #ccc;
            overflow: hidden;
            position: relative;
            margin-left: 5%;
            margin-top: 10%;
            margin-bottom: 10%;
        }
        canvas {
            display: block;
            width: 100%;
            height: 100% !important;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 1% auto;
            padding: 20px;
            border: 1px solid #888;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            width: 50%;
            max-width: 900px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        .filePreview {
            margin-right: 20px;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }
        form {
            margin-top: 50%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        section {
            margin-top: 10px;
        }
        .draggable {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            padding: 10px;
            cursor: move;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .certificate-info {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #007bff;
            padding-left: 25px;
            margin-top: -65px;
            cursor: move;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: auto;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }
        .certificate-info-content {
            display: flex;
            align-items: center;
        }
        .qr-code {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }
        .certificate-info-text {
            display: flex;
            flex-direction: column;
        }
        .certificate-info p {
            margin: 0;
            font-size: 12px;
        }
        .btn-download {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-download:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-download:active {
            background-color: #004494;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-download:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        @media (max-width: 768px) {
            .modal-content {
                flex-direction: column;
                width: 90%;
            }
            .filePreview {
                width: 100%;
                height: auto;
                margin-right: 0;
            }
            .form-container {
                margin-left: 0;
                margin-bottom: 10%;
            }
        }
    </style>
</head>
<body>
    <button id="btn">Upload PDF</button>
    <input type="file" id="fileInput" accept="application/pdf" style="display: none;">

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="{{ url('signPdf') }}" method="POST" id="certifyForm">
                @csrf
                <div id="filePreview" class="filePreview" name="pdf"></div>
                <div class="form-container">
                    <label for="certificateSelect">Select Certificate:</label>
                    <select id="certificateSelect" name="certificate"></select>
                    <button type="button" id="certifyBtn" class="btn-download">Certify PDF</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const certificates = @json($certificates); 
            const fileInput = document.getElementById('fileInput');
            const uploadBtn = document.getElementById('btn');
            const filePreview = document.getElementById('filePreview');
            const modal = document.getElementById('myModal');
            const closeModalBtn = document.getElementsByClassName('close')[0];
            const selectElement = document.getElementById('certificateSelect');
            const certifyBtn = document.getElementById('certifyBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let pdfData = null;
            let namefile = null;

            // Populate certificate select options
            const addedNames = {};
            certificates.forEach(certificate => {
                if (!addedNames[certificate.Subject]) {
                    const option = document.createElement('option');
                    option.value = JSON.stringify(certificate);
                    option.textContent = certificate.Subject;
                    selectElement.appendChild(option);
                    addedNames[certificate.Subject] = true;
                }
            });

            // Open file input dialog
            uploadBtn.addEventListener('click', function() {
                fileInput.click();
            });

            // Handle file input change
            fileInput.addEventListener('change', function() {
                const file = fileInput.files[0];
                if (file) {
                    namefile = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        pdfData = new Uint8Array(e.target.result);
                        renderPdf(pdfData);
                        modal.style.display = 'block';
                    };
                    reader.readAsArrayBuffer(file);
                }
            });

            // Render the first page of the PDF
            function renderPdf(pdfData) {
                pdfjsLib.getDocument({ data: pdfData }).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function() {
                            filePreview.innerHTML = '';
                            filePreview.appendChild(canvas);
                        }).catch(function(error) {
                            console.error('Error rendering PDF page:', error);
                        });
                    }).catch(function(error) {
                        console.error('Error getting PDF page:', error);
                    });
                }).catch(function(error) {
                    console.error('Error loading PDF document:', error);
                });
            }

            // Close modal
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
