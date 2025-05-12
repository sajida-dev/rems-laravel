<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $property->title . ' Application for ' . $type}}</title>
</head>
<body class="bg-gray-100 py-10 px-4">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-600 text-white text-center py-4 px-6 text-xl font-semibold">
            {{ $property->title . ' Application for ' . $type}}
        </div>

        <div class="px-6 py-6 text-gray-800 text-center">
            <p class="text-lg mb-4">
                A new <strong class="capitalize text-pink-600">{{ $type }}</strong> application has been submitted.
            </p>

            <div class="mb-4">
                <p><span class="font-semibold text-gray-700">Applicant:</span> {{ $user->name }}</p>
                <p><span class="font-semibold text-gray-700">Email:</span> {{ $user->email }}</p>
            </div>

            <div class="mb-4">
                <p><span class="font-semibold text-gray-700">Property:</span> {{ $property->title }}</p>
                <p><span class="font-semibold text-gray-700">Location:</span> {{ $property->location }}</p>
            </div>

            <p class="mb-6">Please log in to your dashboard to review and manage this application.</p>

            <div class="text-center">
                <a href="{{ url('/login') }}" class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded">
                    Login
                </a>
            </div>
        </div>

        <div class="bg-gray-50 text-center text-sm text-gray-500 py-4">
            This is an automated message from your Real Estate System.
        </div>
    </div>
</body>
</html>
