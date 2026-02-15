@extends('app')

@section('content')
<style>
    /* Custom Card Styling */
    .movie-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease;
        overflow: hidden;
        cursor: pointer;
        text-decoration: none !important;
        color: #333 !important;
        display: block;
    }

    .movie-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .poster-wrapper {
        position: relative;
        width: 100%;
        padding-top: 150%; /* Aspect ratio 2:3 */
        overflow: hidden;
    }

    .poster-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .movie-info {
        padding: 15px;
    }

    .movie-info h4 {
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 5px;
    }

    .movie-meta {
        height: 20px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .rating-badge {
        background: #f1c40f;
        color: #000;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 12px;
    }
    /* The Shimmer Animation */
    @keyframes shimmer {
        0% { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }

    .shimmer-wrapper {
        background: #f6f7f8;
        background-image: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
        background-repeat: no-repeat;
        background-size: 800px 104px;
        display: inline-block;
        position: relative;
        animation: shimmer 1.2s translateY infinite linear;
        animation-fill-mode: forwards;
    }

    /* Skeleton Card Shapes */
    .skeleton-card {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .skeleton-poster {
        width: 100%;
        padding-top: 150%; /* Match movie poster ratio */
        border-radius: 12px 12px 0 0;
    }

    .skeleton-text {
        height: 15px;
        margin: 15px 15px 5px 15px;
        border-radius: 4px;
    }

    .skeleton-text.short {
        width: 60%;
    }
    #movie-content.row {
        display: flex !important;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: stretch;
    }

    #movie-content.row > [class*='col-'] {
        float: none; 
        display: flex;
        flex-direction: column;
    }

    .movie-card {
        width: 100%;
        flex: 1 0 auto;
        display: flex;
        flex-direction: column;
    }

    .movie-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    #movie-content {
        animation: fadeIn 0.8s ease-in;
    }

    .movie-container {
        min-height: 400px; /* Adjust to match your average card height */
    }

    /* Ensure the fade-in looks smooth for individual cards */
    .movie-card {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="container">
    <h2 class="page-header">Popular Movies</h2>
    
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
                    <img src="{{ $movie->poster }}" 
                         alt="{{ $movie->title }}" 
                         onload="showActualCard('{{ $movie->imdbID }}')">
                </div>
                <div class="movie-info">
                    <h4>{{ $movie->title }}</h4>
                    <div class="movie-meta">
                        <span class="rating-badge">★ {{ $movie->imdbRating }}</span> 
                        <span class="pull-right">{{ $movie->genre }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach 
    </div>
</div>

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
@endsection