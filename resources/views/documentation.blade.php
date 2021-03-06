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

        <style>
            .btn-top {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <span class="navbar-brand">
                <img src="{{ asset('img/bible_api.svg') }}" alt="bible api logo" width="32"> Bible API
            </span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('documentation') }}">
                            Documentation <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="nav-link">Login</a>
                            @endif
                        @endif
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container mt-3">
            <div class="mb-3"><img src="{{ asset('img/bible_api-text.svg') }}" alt="bible api logo text" height="80"></div>
            <p>By developers for developers</p>

            <h1 class="h4">Get all translations</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translations": [
        {
            "name": "King James Version",
            "abbr": "KJV",
            "copyright": {
                "text": "Public Domain"
            }
        }
    ]
}</code></pre>
            <hr>

            <h1 class="h4">Get one translation</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations/{abbr}</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translation": {
        "name": "King James Version",
        "abbr": "KJV",
        "copyright": {
            "text": "Public Domain"
        }
    }
}</code></pre>
            <hr>

            <h1 class="h4">Get all books of a translation</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations/{abbr}/books</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translation": {
        "name": "King James Version",
        "abbr": "KJV",
        "copyright": {
            "text": "Public Domain"
        }
    },
    "books": [
        {
            "name": "Psalm",
            "abbr": "Psa",
            "number": 19
        },
        {
            "name": "Proverbs",
            "abbr": "Prov",
            "number": 20
        }
    ]
}</code></pre>
            <p>Note: <code>number</code> determines the order of the book.</p>
            <hr>

            <h1 class="h4">Get single book of a translation via its name.</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations/{abbr}/books/{name}</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translation": {
        "name": "King James Version",
        "abbr": "KJV",
        "copyright": {
            "text": "Public Domain"
        }
    },
    "book": {
        "name": "Psalm",
        "abbr": "Psa",
        "number": 19
    }
}</code></pre>
            <p>Note: <code>number</code> determines the order of the book.</p>
            <hr>

            <h1 class="h4">Get all chapters of a book.</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations/{abbr}/books/{name}/chapters</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translation": {
        "name": "King James Version",
        "abbr": "KJV",
        "copyright": {
            "text": "Public Domain"
        }
    },
    "book": {
        "name": "Proverbs",
        "abbr": "Prov",
        "number": 20
    },
    "chapters": [
        {
            "number": 1
        },
        {
            "number": 2
        }
    ]
}</code></pre>
            <p>Note: <code>number</code> determines the order of the book and chapters.</p>
            <hr>

            <h1 class="h4">Get a single chapter of a book via its number.</h1>
            <p>Endpoint: <code>GET https://bibleapi/api/translations/{abbr}/books/{name}/chapters/{number}</code></p>
            <p>Sample response:</p>
            <pre><code>{
    "translation": {
        "name": "King James Version",
        "abbr": "KJV",
        "copyright": {
            "text": "Public Domain"
        }
    },
    "book": {
        "name": "Proverbs",
        "abbr": "Prov",
        "number": 20
    },
    "chapter": {
        "number": 1
    }
}</code></pre>
            <p>Note: <code>number</code> determines the order of the book and chapter.</p>
            <hr>

            <footer>
                <p class="text-center text-muted"><small>@include('partials.copyright')</small></p>
            </footer>
        </div>
        <!-- ./container -->

        <a href="#top" class="btn btn-outline-secondary btn-top">Top</a>

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <!-- Bootstrap Script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    </body>
</html>
