@extends('layouts.default')

@section('title', 'Jobmessen in '. $city->name)

@section('metadata')
	<meta name='description' content='{{{ $city->meta_description }}}' />
	<meta name='keywords' content='{{{ $city->keywords }}}' />
@stop

@section('content')
	<div class="span9 alpha">
		<div id="city-description">{{ $city->description }}</div>
		<br>
		<div id="events">
			<h1>Karriere- und Jobmessen in {{{ $city->name }}}</h1>
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
				@if($events->hasMorePages() == false)
					<div class="pull-right">
						<?php $region =  $regions[$events[0]->region_id - 1]; ?>
						<p>{{ HTML::link('jobmessen/'. $region->slug , 'Weitere Messen in '.$region->name, ['class' => 'green']) }}</p>
					</div>
				@endif
			@else
				<p>Keine Veranstaltungen gefunden.</p>
			@endif

			{{ $events->setPath('')->render() }}
		</div>


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