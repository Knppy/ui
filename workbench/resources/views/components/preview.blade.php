@props([
    'file',
    'previewClass' => 'min-h-[350px]',
])

@php
    $path = resource_path('views/examples/'.str_replace('.', '/', $file).'.blade.php');
    $source = is_file($path) ? rtrim(file_get_contents($path)) : "{{-- example not found: $file --}}";
@endphp

@include('examples.'.$file)
