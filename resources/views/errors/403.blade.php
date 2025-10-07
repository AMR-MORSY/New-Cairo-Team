<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Forbidden</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
   
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-gray-500 mb-4">403</h1>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Access Forbidden</h2>

                <div class="mb-6">
                    @php
                        $errorMessage = '';
                        if (isset($exception)) {
                            $errorMessage = $exception->getMessage();
                        } elseif (isset($message)) {
                            $errorMessage = $message;
                        }
                    @endphp

                    @if ($errorMessage)
                        <p class="text-gray-600">{{ $errorMessage }}</p>
                    @else
                        <p class="text-gray-600">You don't have permission to access this resource.</p>
                    @endif
                </div>

                <div class="flex flex-col space-y-2">
                    <button onclick="window.history.back()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go Back
                    </button>

                    <a href="{{ url('/') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        sessionStorage.setItem('was-on-403', 'true');
    </script>

    @livewireScripts
    @fluxScripts
</body>

</html>
