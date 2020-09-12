<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumb as $link)
            @if (array_key_exists('active', $link) && $link['active'])
                <li class="breadcrumb-item active" aria-current="page">{{ $link['label'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
