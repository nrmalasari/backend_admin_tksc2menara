<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test File Protection Middleware</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .test-url {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
            word-break: break-all;
        }
        .instructions {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-test {
            background-color: #007bff;
        }
        .btn-test:hover {
            background-color: #0056b3;
        }
        .result {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 Test File Protection Middleware</h1>
        
        <div class="info result">
            <strong>User ID untuk testing:</strong> {{ $user_id }}
        </div>
        
        <div class="test-url">
            <strong>Test File URL:</strong><br>
            <a href="{{ $file_url }}" target="_blank" id="testLink">{{ $file_url }}</a>
        </div>
        
        <div class="instructions">
            <h3>📋 Instruksi Testing:</h3>
            <ol>
                <li><strong>Tanpa Login:</strong> Klik link di atas. Harusnya muncul error 403 (Forbidden)</li>
                <li><strong>Dengan Login:</strong> Login dulu via API, lalu coba akses lagi dengan Authorization header</li>
                <li>Check log di <code>storage/logs/laravel.log</code> untuk melihat detail akses</li>
            </ol>
            
            <p><strong>Note:</strong> File di folder <code>/storage/api/secure-files/</code> sekarang dilindungi middleware.</p>
        </div>
        
        <div>
            <button class="btn btn-test" onclick="testFileAccess()">🔍 Test File Access</button>
            <button class="btn" onclick="clearResult()">🔄 Clear Result</button>
        </div>
        
        <div id="resultContainer" style="display: none;">
            <h3>📊 Test Result:</h3>
            <div id="result" class="result"></div>
        </div>
        
        <div style="margin-top: 30px;">
            <h3>🔧 Manual Testing dengan curl:</h3>
            <pre style="background: #2d2d2d; color: #fff; padding: 15px; border-radius: 5px;">
# Tanpa login (harusnya 403):
curl -I "{{ $file_url }}"

# Dengan login (gunakan token dari API):
curl -H "Authorization: Bearer {{ $user_id }}" -I "{{ $file_url }}"
            </pre>
        </div>
    </div>
    
    <script>
        async function testFileAccess() {
            const testUrl = document.getElementById('testLink').href;
            const resultDiv = document.getElementById('result');
            const container = document.getElementById('resultContainer');
            
            resultDiv.className = 'result';
            resultDiv.innerHTML = '🔄 Testing...';
            container.style.display = 'block';
            
            try {
                const response = await fetch(testUrl, { method: 'HEAD' });
                
                if (response.status === 403) {
                    resultDiv.className = 'result success';
                    resultDiv.innerHTML = '✅ SUCCESS: Access properly BLOCKED (403 Forbidden)<br>' +
                                         'Middleware is working correctly!';
                } else if (response.status === 200) {
                    resultDiv.className = 'result error';
                    resultDiv.innerHTML = '❌ FAIL: Access allowed (200 OK)<br>' +
                                         'Middleware might not be working properly.';
                } else {
                    resultDiv.className = 'result info';
                    resultDiv.innerHTML = `ℹ️ Status: ${response.status} ${response.statusText}`;
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.innerHTML = '❌ Error: ' + error.message;
            }
        }
        
        function clearResult() {
            document.getElementById('resultContainer').style.display = 'none';
            document.getElementById('result').innerHTML = '';
        }
        
        // Auto-test on page load
        window.addEventListener('load', function() {
            // Uncomment line below for auto-test
            // testFileAccess();
        });
    </script>
</body>
</html>