<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @head

    @vite(['workbench/resources/css/app.css'])
</head>
<body class="bg-background text-foreground min-h-screen font-sans antialiased" x-data>
    {{ $slot }}
</body>
</html>
