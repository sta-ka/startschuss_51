@extends('layouts.default')

@section('title', 'Das Karriereportal')

@section('metadata')
	<meta name='description' content='startschuss-karriere.de bietet einen detaillierten Überblick über Karriere- und Jobmessen in Deutschland, Österreich und der Schweiz.' />
	<meta name='keywords' content='karriere, jobmesse, karrieremesse, firmenkontaktgespräch, firmenmesse' />
@stop

@section('content')
	<div class="span9 alpha">
		<div id="events">
			<h1>Karriere- und Jobmessen</h1>
			<ul>
			@if($events)
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
							<td class="date">
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
								<strong>Veranstalter: </strong>{{ HTML::linkRoute('veranstalter', $event->organizer->name, [$event->organizer->slug]) }}
							</td>
							<td style="width: 120px;" itemprop="location" itemscope itemtype="http://schema.org/Place">
								<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
									<span  itemprop="addressLocality" content="{{{ $event->location }}}">
										{{{ $event->location }}}<br>
										<meta itemprop="addressRegion" content="{{{ $event->region->name }}}">
									</span>
								</div>
							</td>
						</tr>
					</table>
				</li>
				@endforeach
			@else
				<li>Keine Veranstaltungen gefunden.</li>
			@endif
			</ul>
			<div class="pull-right">
				<p>{{ HTML::linkRoute('messekalender', 'Alle Messen', [], ['class' => 'green']) }}</p>
			</div>
		</div>
	</div>

	<div class="span3 omega">
		<div id="organizers">

			@include('start/partials/searchbox_events')

			<h2>Veranstalter</h2>
			<ul>
				@foreach ($organizers as $organizer)
				<li>
					<div class="image">
						@if($organizer->logo)
							{{ HTML::imageLink('veranstalter/'.$organizer->slug, 'uploads/logos/medium/'.$organizer->logo, $organizer->name) }}
						@else
							{{ HTML::linkRoute('veranstalter', $organizer->name, [$organizer->slug]) }}
						@endif
					</div>
				</li>
				@endforeach
			</ul>
			<p>{{ HTML::linkRoute('veranstalterdatenbank', 'Alle Veranstalter', [], ['class' => 'green']) }}</p>
		</div>

		<div id="article-teaser">
			<h2>Neueste Artikel</h2>
				@foreach($articles as $article)
				<div class="article-preview">
					<h4 class="article-heading">{{ HTML::link('karriereratgeber/'.$article->slug, Str::limit($article->title, 35)) }}</h4>
					<p>{{ Str::limit($article->body, 120) }}</p>
					<div class="pull-right">{{ HTML::link('karriereratgeber/'.$article->slug, 'Mehr lesen', ['class' => 'btn btn-success btn-xs']) }}</div>
				</div>
			@endforeach
			{{ HTML::link('karriereratgeber', 'Zum Karriereratgeber', ['class' => 'btn btn-primary btn-block']) }}
		</div>
	</div>	
@stop