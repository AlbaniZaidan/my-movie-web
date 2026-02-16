@extends('app')
@section('content')

<div class="container detail-container">
    
    <div>
        <h1 class="detail-title">{{ $movie->title }}</h1>
        
        <div class="detail-meta-row">
            <span>{{ $movie->year }}</span>
            <span>|</span>
            <span>{{ $movie->runtime }}</span>
        </div>

        <div class="detail-meta-row">
            <span class="detail-rating-badge">{{ $movie->rated }}</span>
            <span class="detail-star-score">★ {{ $movie->imdbRating }}/10</span>
        </div>
    </div>

    <div class="detail-hero">
        
        <div class="detail-poster-wrapper">
            <img 
                src="{{ $movie->poster == 'N/A' ? asset('img/NoImage.jpg') : $movie->poster }}" 
                alt="{{ $movie->title }}"
                onerror="this.onerror=null; this.src='{{ asset('img/NoImage.jpg') }}';"
            >
        </div>

        <div class="detail-info">
            
            <div class="genre-list">
                @foreach(explode(',', $movie->genre) as $genre)
                    <span class="genre-pill">{{ trim($genre) }}</span>
                @endforeach
            </div>

            <div class="plot-text">
                {{ $movie->plot }}
            </div>

            <button class="btn-favorite" 
                    id="favorite-btn" 
                    data-id="{{ $movie->imdbID }}" 
                    data-favorited="{{ $isFavorited ? 'true' : 'false' }}">
                {{ $isFavorited ? 'Remove from Favorites' : '+ Add to Favorites' }}
            </button>

            <div class="credits-box">
                <div class="credit-row">
                    <span class="credit-label">Director</span>
                    <span class="credit-value">{{ $movie->director }}</span>
                </div>
                <div class="credit-row">
                    <span class="credit-label">Writers</span>
                    <span class="credit-value">{{ $movie->writer }}</span>
                </div>
                <div class="credit-row" style="border-bottom: none;">
                    <span class="credit-label">Stars</span>
                    <span class="credit-value">{{ $movie->actors }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#favorite-btn').click(function() {
            var btn = $(this);
            var movieId = btn.data('id');
            var isFavorited = btn.data('favorited'); // Fixed missing semicolon

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (isFavorited) {
                $.ajax({
                    url: '/favorites/' + movieId,
                    type: 'DELETE',
                    success: function(response) {
                        btn.data('favorited', false);
                        btn.text('+ Add to Favorites');
                        // Optional: Reset color
                        // btn.css('background-color', '#f5c518');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Debugging
                    }
                });
            } else {
                $.ajax({
                    url: '/favorites',
                    type: 'POST',
                    data: { movie_id: movieId },
                    success: function(response) {
                        btn.data('favorited', true);
                        btn.text('Remove from Favorites');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Debugging
                    }
                });
            }
        });
    });
</script>
@endsection