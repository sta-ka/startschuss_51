@extends('layouts.organizer')

@section('title', 'Veranstaltungslogo bearbeiten')

@section('content')
	@include('organizer.events.profile.partials.breadcrumb')

	<div class="span3 alpha">
		@include('organizer.events.profile.partials.nav_bar')
	</div>
	<div class="span6">
		@include('partials/validation_errors')

		@if($event->logo)
			{{ HTML::image('uploads/logos/small/'.$event->logo, $event->name) }}
			<br><br>
			{{ HTML::link('organizer/profile/'.$event->id.'/delete-logo', 'Logo löschen', ['class' => 'btn btn-danger btn-sm']) }}
		@else
			<p id="filename"></p>
			
			{{ Form::open(['url' => 'organizer/profile/'.$event->id.'/update-logo', 'files' => true]) }}

			<div class="form-group">
				<button class="btn btn-default btn-sm" id="browse-file" >Logo auswählen</button>
				{{ Form::file('logo', ['id' => 'logo']) }}
			</div>

			<div>
				{{ Form::submit('Hochladen', ['class' => 'btn btn-primary btn-sm']) }}
			</div>

			{{ Form::close('') }}
		@endif
	</div>
	<div class="span3 omega">
		&nbsp;
	</div>
@stop