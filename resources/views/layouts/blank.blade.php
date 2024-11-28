<!DOCTYPE html>
<html lang="EN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AMS</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#FFF3E0]">
    <div class="min-h-screen flex">
        <main class="flex-1 p-8">
            @if (session('message'))
                <div 
                    x-data="{ show: true }" 
                    x-init="setTimeout(() => show = false, 3000)" 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-300" 
                    x-transition:enter-start="opacity-0 transform translate-y-2" 
                    x-transition:enter-end="opacity-100 transform translate-y-0" 
                    x-transition:leave="transition ease-in duration-300" 
                    x-transition:leave-start="opacity-100 transform translate-y-0" 
                    x-transition:leave-end="opacity-0 transform translate-y-2" 
                    class="fixed top-4 right-4 bg-green-500 text-white px-6 py-2 rounded-md shadow-lg">
                    <p>{{ session('message') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div 
                    x-data="{ show: true }" 
                    x-init="setTimeout(() => show = false, 3000)" 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-300" 
                    x-transition:enter-start="opacity-0 transform translate-y-2" 
                    x-transition:enter-end="opacity-100 transform translate-y-0" 
                    x-transition:leave="transition ease-in duration-300" 
                    x-transition:leave-start="opacity-100 transform translate-y-0" 
                    x-transition:leave-end="opacity-0 transform translate-y-2" 
                    class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-md shadow-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>