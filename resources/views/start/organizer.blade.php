@extends('layouts.default')

@section('title', 'Veranstalter: '. $organizer->name)

@section('metadata')
	<meta name='description' content='{{{ $organizer->meta_description }}}' />
	<meta name='keywords' content='{{{ $organizer->keywords }}}' />
@stop

@section('content')
	<div id="organizer-details" class="span9 alpha">
		<h1>{{{ $organizer->name }}}</h1>

		<div id="profile">
			<h3>Profil</h3>
			@if($organizer->profile)
				<p>{{ $organizer->profile }}</p>
			@else
				<p>Noch kein Profil hinterlegt.</p>
			@endif
		</div>

		<br>

		<div id="events" >
			<h2>Aktuelle Messen dieses Veranstalters</h2>
			@if(count($events) > 0)
				<ul>
				@foreach($events as $event)
					<li itemscope itemtype="http://schema.org/Event">
						<table>
							<tr>
								<td style="width: 130px; height: 50px">
									@if($event->logo)
										{{ HTML::image('uploads/logos/small/'.$event->logo) }}
									@endif
								</td>
								<td colspan="3">
									<span class="name" itemprop="name">{{ HTML::linkRoute('messe', $event->name, [$event->slug]) }}</span>
									<meta itemprop="description" content="Jobmesse">
									<meta itemprop="url" content="{{ URL::route('messe', [$event->slug]) }}">
								</td>
							</tr>
							<tr>
								<td>
									@if($event->start_date == $event->end_date)
										<span itemprop="startDate" content="{{{ Date::format($event->start_date, 'ISO')}}}">
											{{{ Date::monthDate($event->start_date, $event->end_date) }}}
										</span>
									@else
										<span itemprop="startDate" content="{{{ Date::format($event->start_date, 'ISO')}}}">
											{{{ Date::format($event->start_date, 'day').'. -' }}}
										</span>
										<span itemprop="endDate" content="{{{ Date::format($event->end_date, 'ISO')}}}">
											{{{ Date::monthDate($event->end_date) }}}
										</span>
									@endif
								</td>
								<td colspan="2">
									&nbsp;
								</td>
								<td style="width: 120px;" itemprop="location" itemscope itemtype="http://schema.org/Place">
									<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
										<span  itemprop="addressLocality"content="{{{ $event->location }}}">
											{{{ $event->location }}}<br>
											<meta itemprop="addressRegion"content="{{{ $event->region->name }}}">
										</span>
									</div>
								</td>
							</tr>
						</table>
					</li>
				@endforeach
				</ul>
				<div class="pull-right">
					<p>{{ HTML::linkRoute('messekalender', 'Alle Messen', [], ['class' => 'green']) }}</p>
				</div>
			@else
				<p>Keine Veranstaltungen gefunden.</p>
			@endif
		</div>
	</div>
	<div id="organizer" class="span3 omega">
		@if($organizer->logo)
			<div>
				{{ HTML::image('uploads/logos/big/'.$organizer->logo, $organizer->name) }}
				<br><br>
			</div>
		@endif

		<h5>Adresse</h5>
		@if($organizer->address1 || $organizer->address2 || $organizer->address3)			
			@if($organizer->address1)
				{{{ $organizer->address1 }}}<br>
			@endif
			@if($organizer->address2)
				{{{ $organizer->address2 }}}<br>
			@endif
			@if($organizer->address3)
				{{{ $organizer->address3 }}}<br>
			@endif
		@else
			-
			<br>
		@endif
		<br>
		<h5>Webseite des Veranstalters</h5>
		@if($organizer->website)
			{{ HTML::link($organizer->website, $organizer->name, ['class' => 'green', 'target' => '_blank']) }}
		@else
			-
		@endif

		<br>
		<br>

		@if($organizer->facebook || $organizer->twitter)
			<h5>Social Media</h5>
			@if($organizer->facebook)
				{{ HTML::imageLink($organizer->facebook, 'assets/img/icons/facebook.png', $organizer->name, ['target' => '_blank']) }}
			@endif
			@if($organizer->twitter)
				{{ HTML::imageLink($organizer->twitter, 'assets/img/icons/twitter.png', $organizer->name, ['target' => '_blank']) }}
			@endif
		@endif
	</div>
@stop
