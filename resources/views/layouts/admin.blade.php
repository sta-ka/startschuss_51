<!DOCTYPE html> 
<html lang="de">
	<head>
		<meta charset="utf-8"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>@yield('title') | startschuss-karriere.de</title>

		<link rel="shortcut icon" href="{{ URL::asset('assets/img/favicon.ico') }}" type="image/x-icon" />

		<link rel="stylesheet" href="{{ URL::asset('assets/css/all.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/backend/jquery.datatables.css') }}">

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
				
				<div class="navbar navbar-default navbar-static-top">
					<div class="navbar container_12">
						<ul class="nav navbar-nav navbar-left">
							<li class="dropdown {{ set_active('*dashboard*', false) }}">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Dashboard
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li {{ set_active(['*logged-data*', '*requested-events*']) }}>
										{{ HTML::link('admin/dashboard/logged-data', 'Aktuelles') }}
									</li>
									<li {{ set_active('*login*') }}>
										{{ HTML::link('admin/dashboard/logins', 'Statistik') }}
									</li>
									<li {{ set_active('*revision*') }}>
										{{ HTML::link('admin/dashboard/revisions', 'Änderungen') }}
									</li>
								</ul>
							</li>
							<li {{ set_active(['*admin/users*']) }}>
								{{ HTML::link('admin/users', 'Nutzer') }}
							</li>
							<li class="dropdown {{ set_active(['*admin/applications*', '*admin/applicants*' ,'*admin/events*', '*admin/jobs*', '*admin/companies*', '*admin/organizers*'], false) }} ">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Gruppen
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li {{ set_active('*admin/events*') }}>
										{{ HTML::link('admin/events', 'Messen') }}
									</li>
									<li {{ set_active('*admin/companies*') }}>
										{{ HTML::link('admin/companies', 'Unternehmen') }}
									</li>
									<li {{ set_active('*admin/organizers*') }}>
										{{ HTML::link('admin/organizers', 'Veranstalter') }}
									</li>
									<li {{ set_active('*admin/jobs*') }}>
										{{ HTML::link('admin/jobs', 'Stellenangebote') }}
									</li>
									<li {{ set_active('*admin/applicants*') }}>
										{{ HTML::link('admin/applicants', 'Bewerber') }}
									</li>
									<li {{ set_active('*admin/applications*') }}>
										{{ HTML::link('admin/applications', 'Bewerbungen') }}
									</li>
								</ul>
							</li>
							<li class="dropdown {{ set_active(['*regions*', '*cities*', '*articles*'], false) }} ">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Sonstiges
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li {{ set_active('*admin/articles*') }}>
										{{ HTML::link('admin/articles', 'Ratgeber') }}
									</li>
									<li {{ set_active('*admin/regions*') }}>
										{{ HTML::link('admin/regions', 'Regionen') }}
									</li>
									<li {{ set_active('*admin/cities*') }}>
										{{ HTML::link('admin/cities', 'Städte') }}
									</li>
								</ul>
							</li>	
							<li {{ set_active(['*admin/settings*']) }}>
								{{ HTML::link('admin/settings', 'Einstellungen') }}
							</li>
						</ul>
						@include('layouts/partials/statusbox')
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

		</div> <!-- Ende 'wrapper' -->

		@include('layouts/partials/footer_front')

		<script src="{{ URL::asset('assets/js/jquery.js') }}"></script>
		
		<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
		<script src="{{ URL::asset('assets/js/tinymce/tinymce.min.js') }}"></script>
		<script src="{{ URL::asset('assets/js/jquery.datatables.js') }}"></script>
		<script src="{{ URL::asset('assets/js/datatables.sorting.js') }}"></script>
		<script src="{{ URL::asset('assets/js/backend/general.js') }}"></script>

		@yield('scripts')
		
	</body>
</html>