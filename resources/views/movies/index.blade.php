@extends('app')

@section('content')

<script>
    function showActualCard(imdbID) {
        // Set the 2-second delay
        setTimeout(function() {
            var skeleton = document.getElementById('skeleton-' + imdbID);
            var card = document.getElementById('card-' + imdbID);
            
            if (skeleton && card) {
                skeleton.style.display = 'none';
                card.style.display = 'block';
            }
        }, 2000); // 2000ms = 2 seconds
    }
</script>


<div class="container">
    @if (Request::get('filter') == 'favorites')    
        @if (isset($favoritesMovies) && !$favoritesMovies->isEmpty())
        <h2>Your Favorites</h2>
        <div id="movie-content" class="row">
        @foreach ($favoritesMovies as $movie)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 movie-container">
            <div class="skeleton-card" id="skeleton-{{ $movie->imdbID }}">
                <div class="shimmer-wrapper skeleton-poster"></div>
                <div class="shimmer-wrapper skeleton-text"></div>
                <div class="shimmer-wrapper skeleton-text short"></div>
            </div>

            <a href="{{ url('movies/' . $movie->imdbID) }}" 
               class="movie-card" 
               id="card-{{ $movie->imdbID }}" 
               style="display: none;">
                <div class="poster-wrapper">
                    <img 
                        src="{{ $movie->poster == 'N/A' ? asset('img/NoImage.jpg') : $movie->poster }}" 
                        alt="{{ $movie->title }}" 
                        onload="showActualCard('{{ $movie->imdbID }}')"
                        onerror="this.onerror=null; this.src='{{ asset('img/NoImage.jpg') }}'; showActualCard('{{ $movie->imdbID }}');"
                    >
                </div>
                <div class="movie-info">
                    <h4>{{ $movie->title }}</h4>
                    <div class="movie-meta">
                        <div class="rating-badge">★ {{ $movie->imdbRating }}</div> 
                        <div class="pull-right">{{ $movie->genre }}</div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach 
    </div>
    @else
        <div class="alert alert-warning">
            You haven't added any favorites yet! <a href="{{ url('/movies') }}">Browse movies</a>
        </div>
    @endif
    @else
        <h2>All Movies</h2>
        <div id="movie-content" class="row">
        @foreach ($movies as $movie)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 movie-container">
            <div class="skeleton-card" id="skeleton-{{ $movie->imdbID }}">
                <div class="shimmer-wrapper skeleton-poster"></div>
                <div class="shimmer-wrapper skeleton-text"></div>
                <div class="shimmer-wrapper skeleton-text short"></div>
            </div>

            <a href="{{ url('movies/' . $movie->imdbID) }}" 
               class="movie-card" 
               id="card-{{ $movie->imdbID }}" 
               style="display: none;">
                <div class="poster-wrapper">
                    <img 
                        src="{{ $movie->poster == 'N/A' ? asset('img/NoImage.jpg') : $movie->poster }}" 
                        alt="{{ $movie->title }}" 
                        onload="showActualCard('{{ $movie->imdbID }}')"
                        onerror="this.onerror=null; this.src='{{ asset('img/NoImage.jpg') }}'; showActualCard('{{ $movie->imdbID }}');"
                    >
                </div>
                <div class="movie-info">
                    <h4>{{ $movie->title }}</h4>
                    <div class="movie-meta">
                        <div class="rating-badge">★ {{ $movie->imdbRating }}</div> 
                        <div class="pull-right">{{ $movie->genre }}</div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach 
    </div>
    @endif
    
</div>
@endsection