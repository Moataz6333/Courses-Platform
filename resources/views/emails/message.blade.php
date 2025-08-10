<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md border">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">New Message from Udamy</h2>

        <div class="mb-4">
            <p class="text-gray-600"><strong>From:</strong> <span class="text-gray-800">{{ $from }}</span></p>
            <p class="text-gray-600"><strong>To:</strong> <span class="text-gray-800">{{ $to }}</span></p>
            <p class="text-gray-600"><strong>Subject:</strong> <span class="text-gray-800">{{ $subject }}</span></p>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Message:</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $body }}</p>
        </div>

        <div class="mt-6 text-sm text-gray-500 text-center">
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>
</html>
