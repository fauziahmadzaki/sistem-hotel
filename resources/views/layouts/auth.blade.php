<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Login Page')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-toast></x-toast>
    <div class="flex flex-col justify-center items-center min-h-svh gap-5 bg-neutral-100">
        <h1 class="text-xl text-violet-500 font-semibold">Hotelio</h1>
        <div class="p-5 shadow-md rounded-lg max-w-lg min-w-sm space-y-5 bg-white">
            <div id="cardHeader" class="text-center">
                <h1 class="text-2xl font-bold text-violet-500">
                    Login
                </h1>
                <p class="text-gray-600">Silahkan Login untuk mengakses semua fitur</p>
            </div>
            @yield('contents')
        </div>
    </div>
</body>

</html>