<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Free Bible API for developers">
        <meta name="keywords" content="free bible api, for developers, free, bible, api, developer, web, html, css, javascript, php, laravel, mysql, forge,
        genesis, exodus, leviticus, numbers, deuteronomy, joshua, judges, ruth, 1 samuel, 2 samuel, 1 kings, 2 kings,
        1 chronicles, 2 chronicles, ezra, nehemiah, esther, job, psalms, proverbs, ecclesiastes, song of solomon,
        isaiah, jeremiah, lamentations, ezekiel, daniel, hosea, joel, amos, obadiah, jonah, micah, nahum, habakkuk,
        zephaniah, haggai, zechariah, malachi, matthew, mark, luke, john, acts, romans, 1 corinthians, 2 corinthians,
        galatians, ephesians, philippians, colossians, 1 thessalonians, 2 thessalonians, 1 timothy, 2 timothy, titus,
        philemon, hebrews, james, 1 peter, 2 peter, 1 john, 2 john, 3 john, jude, revelation">
        <meta name="author" content="Davina Leong">

        <title>{{ config('app.name') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('img/bible_api.svg') }}">

        <!-- Bootstrap Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>
                    @endif
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <img src="{{ asset('img/bible_api-text.svg') }}" alt="Bible API text logo">
                </div>

                <h1>Documentation</h1>
                <p>Coming soon!</p>
            </div>
        </div>

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <!-- Bootstrap Script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    </body>
</html>
