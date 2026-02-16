<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MyMovie</title>

	{{-- <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> --}}
	<link rel="stylesheet" href="{{ asset('/css/my-movie.css') }}">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

</head>
<body>
	<div class="topnav">
		<div class="topnav-center">
			<a class="navbar-brand" href="/movies">MyMovie</a>
			@if (Auth::check())
			<div class="nav-search-wrapper" style="position: relative;"> 
				<form action="{{ url('/movies') }}" method="GET" class="search-form">
					<input type="text" 
						id="search-input" 
						name="search" 
						autocomplete="off" 
						placeholder="Search Title..." 
						value="{{ Request::get('search') }}">
					<button type="submit">
						<i class="glyphicon glyphicon-search"></i>
					</button>
				</form>
				
				<div id="search-dropdown" class="search-dropdown" style="display: none;"></div>
			</div>
			<div>
				<a class="nav-link" href="{{ url('/movies') }}?filter=favorites">Your Favorites</a>
				<a class="nav-link" href="/logout">Logout</a>
			</div>
			@else
				
			@endif
		</div>
	</div>

	<div class="content">
		@yield('content')
	</div>

	<div class="footer">
		<div class="container-center">
			<p>&copy; 2026 My Movie App. All rights reserved.</p>
		</div>
	</div>

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			var typingTimer;
			var doneTypingInterval = 300; // Wait 300ms before searching (performance)
			var $input = $('#search-input');
			var $dropdown = $('#search-dropdown');

			// 1. Listen for typing
			$input.on('keyup', function() {
				clearTimeout(typingTimer);
				var query = $(this).val();

				if (query.length > 2) {
					typingTimer = setTimeout(function() {
						fetchResults(query);
					}, doneTypingInterval);
				} else {
					$dropdown.hide();
				}
			});

			// 2. Fetch Data from our new Controller method
			function fetchResults(query) {
				$.ajax({
					url: '/movies/live-search',
					type: 'GET',
					data: { query: query },
					success: function(data) {
						$dropdown.empty();

						if (data.length > 0) {
							// Loop through results and build HTML
							$.each(data, function(index, movie) {
								var poster = (movie.Poster && movie.Poster !== 'N/A') ? movie.Poster : '{{ asset("img/NoImage.jpg") }}';
								var link = '{{ url("movies") }}/' + movie.imdbID;

								var html = `
									<a href="${link}" class="search-item">
										<img src="${poster}" alt="poster">
										<div class="search-info">
											<span class="search-title">${movie.Title}</span>
											<span class="search-year">${movie.Year}</span>
										</div>
									</a>
								`;
								$dropdown.append(html);
							});
							$dropdown.show();
						} else {
							// Optional: Show "No results"
							$dropdown.html('<div style="padding:10px; color:#888; text-align:center;">No results found</div>').show();
						}
					}
				});
			}

			// 3. Hide dropdown when clicking outside
			$(document).click(function(e) {
				if (!$(e.target).closest('.nav-search-wrapper').length) {
					$dropdown.hide();
				}
			});
		});
	</script>

	@yield('scripts')
</body>
</html>
