@extends('layouts.default')
<?php  
	$title = Request::segment(2);
	$title = isset($title) ? ' - '. Str::upper($title) : null;
?>
@section('title', 'Veranstalterdatenbank' . $title)

@section('metadata')
	<meta name='description' content='Überblick über die verschiedenen Veranstalter von Karriere- und Jobmessen.' />
	<meta name='keywords' content='messeveranstalter, jobmessen' />
@stop

@section('content')
	<div class="span8 alpha">
		<div id="letters">
			<?php $alphabet = str_split('abcdefghijklmnopqrstuvwxyz'); ?>
			<ul>
				<li>
					@if(Request::segment(2) == '')
						<strong>Alle</strong>
					@else
						{{ HTML::linkRoute('veranstalterdatenbank', 'Alle') }}
					@endif
				</li>
			@foreach($alphabet as $letter)
				<li>
					@if(Request::segment(2) == $letter)
						<strong>{{{ Str::upper($letter) }}}</strong>
					@else
						{{ HTML::linkRoute('veranstalterdatenbank', Str::upper($letter), [$letter]) }}
					@endif
				</li>
			@endforeach
			</ul>
		</div>
		<br>
		<div id="organizer-database">
			@if(count($organizers) > 0)
				<ul>
				@foreach($organizers as $organizer)
					<li>
						<table>
							<tr>
								<td style="width: 50px; height: 25px">
									@if($organizer->logo)
										{{ HTML::image('uploads/logos/small/'.$organizer->logo, $organizer->name) }}
									@endif
								</td>
								<td>
									{{ HTML::linkRoute('veranstalter', $organizer->name, [$organizer->slug]) }}
								</td>
						
							</tr>
						</table>
					</li>
				@endforeach
				</ul>
			@else
				<ul>
					<li>Keinen Veranstalter gefunden.</li>
				</ul>
			@endif
		</div>

		{{ $organizers->setPath('')->render() }}
			
	</div>

	<div class="span4 omega">
		<h3>Jobmesse eintragen</h3>
		<p>
			Sind Sie Veranstalter einer Karrieremesse und möchten sich auf startschuss-karriere.de präsentieren?<br />
			Tragen Sie hier ihre Jobmesse ein.
		</p>
		<p>
			{{ HTML::link('jobmesse-eintragen', 'Jobmesse eintragen', ['class' => 'green']) }}
		</p>
	</div>
@stop