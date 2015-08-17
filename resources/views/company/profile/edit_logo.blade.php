@extends('layouts.company')

@section('title', 'Unternehmenslogo bearbeiten')

@section('content')
	@include('company.profile.partials.breadcrumb')

	<div class="span3 alpha">
		@include('company.profile.partials.nav_bar')
	</div>
	<div class="span6">
		@include('partials/validation_errors')

		@if($company->logo)
			{{ HTML::image('uploads/logos/medium/'.$company->logo, $company->name) }}
			<br><br>
			{{ HTML::link('company/profile/'.$company->id.'/delete-logo', 'Logo löschen', ['class' => 'btn btn-danger btn-sm']) }}
		@else
			<p id="filename"></p>
			
			{{ Form::open(['url' => 'company/profile/'.$company->id.'/update-logo', 'files' => true]) }}

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