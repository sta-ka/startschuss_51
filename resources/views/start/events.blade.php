@extends('layouts.default')

@section('title', 'Jobmessekalender')

@section('metadata')
	<meta name='description' content='startschuss-karriere.de bietet in einem Jobmessekalender einen detaillierten Überblick über zukünftige Karriere- und Jobmessen.' />
	<meta name='keywords' content='karriere, jobmessekalender, firmenkontaktgespräch' />
@stop

@section('content')
	<div class="span9 alpha">
		@if(Input::has('stadt')) 
			<div id="search-results">
				@if($events->total() > 0)
					<div class="alert alert-success">
						<h4 class="alert-heading">Erfolg!</h4>
						Ihre Suche ergab <strong>{{{ $events->total() }}}</strong> Treffer!
					</div>
				@else
					<div class="alert alert-danger">
						<h4 class="alert-heading">Keine Treffer!</h4>
						Ihre Suche ergab keine Treffer!
						<br><br>
						{{ HTML::linkRoute('messekalender', 'Alle Jobmessen anzeigen') }}
					</div>
				@endif
			</div>
		@endif

        @if(count($events) > 0)
            <div id="events">
                <h1>Karriere- und Jobmessen</h1>

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
				</ul>

                @if($events->lastPage() <= 1)
					<div class="pull-right">
						<p>{{ HTML::linkRoute('messekalender', 'Alle Messen', [], ['class' => 'green']) }}</p>
					</div>
				@endif

				{{ $events->setPath('')->appends(Request::except('page'))->render(new \Illuminate\Pagination\SimpleBootstrapThreePresenter($events)) }}
            </div>
        @endif

        @include('start/partials/missing_event')

	</div>

	<div class="span3 omega">
		
		@include('start/partials/searchbox_events')

		<div id="regions">
			<h3>Messen in Ihrer Region</h3>
			<ul>
			<?php $i = 0; ?>
			@foreach($regions as $region)
				<?php $i++; ?>
				@if($i == 17)
					<li>&nbsp;</li>
				@endif
				<li>{{ HTML::linkRoute('messen', $region->name, [$region->slug]) }}</li>
			@endforeach
			</ul>
		</div>
	</div>
@stop