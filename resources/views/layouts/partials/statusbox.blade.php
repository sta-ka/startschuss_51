@if(Sentry::check())
	<?php
		// get logged in user
		$user = Sentry::getUser();

		// get group of user
		$group = $user->getGroups()->first()->name;

		if($group == 'admin')
			$link = 'admin/';
		elseif($group == 'organizer')
			$link = 'organizer/';
		elseif($group == 'company')
			$link = 'company/';
		elseif($group == 'applicant')
			$link = 'applicant/';
		else
			$link = '';
	?>

	<ul class="nav navbar-nav navbar-right">
		@if(isset($frontend))
			<li>{{ HTML::link($link.'dashboard', 'Mein Konto') }}</li>
			<li class="divider-vertical"></li>
		@endif

		<li>{{ HTML::linkRoute('logout', 'Logout') }}</li>							
	</ul>
@else
	<ul class="nav navbar-nav navbar-right">
		<li class="{{ set_active('login*') }}">
			{{ HTML::link('login', 'Login') }}
		</li>
		<li class="divider-vertical"></li>
		<li class="{{ set_active('registrieren*') }}">
			{{ HTML::link('registrieren', 'Registrieren') }}
		</li>							
	</ul>
@endif