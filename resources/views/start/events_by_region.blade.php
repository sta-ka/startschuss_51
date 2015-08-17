@extends('layouts.default')

<?php switch($region->name) {
		case 'Saarland':
			$prefix = 'im';
			break;
		case 'Schweiz':
			$prefix = 'in der';
			break;
		default:
			$prefix = 'in';
} ?>

@section('title', 'Jobmessen '.$prefix.' '.$region->name)

@section('metadata')
	<meta name='description' content='{{{ $region->meta_description }}}' />
	<meta name='keywords' content='{{{ $region->keywords }}}' />
@stop

@section('content')
	<div class="span9 alpha">
		<div id="region-description">{{ $region->description }}</div>
		<br>
		<div id="events">

			<h1>Karriere- und Jobmessen {{{ $prefix .' '. $region->name }}}</h1>
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
						<p>{{ HTML::linkRoute('messekalender', 'Alle Messen', [], ['class' => 'green']) }}</p>
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
				
				@if(urldecode(Request::Segment(2)) == urldecode($region->slug))
					<li><strong>{{{ $region->name }}}</strong></li>
				@else
					<li>{{ HTML::linkRoute('messen', $region->name, [$region->slug]) }}</a></li>
				@endif
			@endforeach
			</ul>
		</div>
	</div>

@stop