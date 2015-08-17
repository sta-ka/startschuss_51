@extends('layouts.default')

@section('title', 'Seite nicht gefunden')

@section('content')
	<?php 

		$link = '';

		if(Sentry::check())
		{
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
		}
	?>
	<div class="span2 alpha">
		&nbsp;
	</div>
	<div class="span7">
		<h3>Seite nicht gefunden</h3>

		<p>Die gesuchte Seite konnte nicht gefunden werden. Überprüfen Sie die URL oder versuchen es erneut.</p>

		@if($link)
			{{ HTML::link($link.'dashboard', 'Zu meinem Konto', array('class' => 'green')) }}
		@else
			{{ HTML::link('home', 'Zurück zur Startseite', array('class' => 'green')) }}
		@endif

		<br><br>

		<p>Sollte das Problem bestehen bleiben, können Sie sich gerne an uns wenden.</p>
		{{ HTML::link('kontakt', 'Kontakt aufnehmen', array('class' => 'green')) }}
	</div>
	<div class="span3 omega">
		&nbsp;
	</div>
@stop