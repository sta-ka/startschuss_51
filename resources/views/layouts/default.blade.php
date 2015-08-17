<!DOCTYPE html> 
<html lang="de">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<title>@yield('title') | startschuss-karriere.de</title>

		<link rel="shortcut icon" href="{{ URL::asset('assets/img/favicon.ico') }}" type="image/x-icon" />

		<link rel="stylesheet" href="{{ URL::asset('assets/css/all.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/front/styles.css') }}">

		@yield('metadata')
	</head>

	<body>
		<div id="wrap">
		
			<div id="header">
				<div id="background">
					<div class="content-area container_12">
						{{ HTML::imageLink('home', 'assets/img/logo.png', 'startschuss-karriere', ['title' => 'Startseite']) }}
					</div>
				</div>

				<div class="navbar navbar-default">
					<div class="navbar container_12">
						<ul class="nav navbar-nav navbar-left">
							<li {{ set_active('*home') }}>
								{{ HTML::linkRoute('home', 'Home') }}
							</li>
							<li {{ set_active(['*jobmesse/*', '*jobmessekalender*', '*jobmessen/*']) }}>
								{{ HTML::linkRoute('messekalender', 'Messen') }}
							</li>
							{{--<li {{ set_active(['*jobs', '*jobs/*', '*job/*', '*unternehmen/*']) }}>--}}
								 {{--{{ HTML::linkRoute('jobs', 'Karriere') }}--}}
							</li>
							<li {{ set_active('*veranstalter*') }}>
								{{ HTML::linkRoute('veranstalterdatenbank', 'Veranstalter') }}
							</li>
							<li {{ set_active('*karriereratgeber*') }}>
								{{ HTML::linkRoute('karriereratgeber', 'Ratgeber') }}
							</li>
							<li {{ set_active('*kontakt*') }}>
								{{ HTML::linkRoute('kontakt', 'Kontakt') }}
							</li>
						</ul>

						@include('layouts/partials/statusbox', ['frontend' => true])
					</div>
				</div>
			</div>

			<div id="content">
				{{ Notification::display() }}
				<div id="main" class="container_12">
					<div class="span12">
						@yield('content')
					</div>
				</div>
			</div>

		</div>
			
		@include('layouts/partials/footer_front')

		@yield('scripts')

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-43287908-1', 'startschuss-karriere.de');
			ga('send', 'pageview');
		</script>
	</body>
</html>