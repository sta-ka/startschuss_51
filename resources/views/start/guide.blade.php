@extends('layouts.default')

@section('title', 'Der Karriere- und Jobmesse-Karriereratgeber')

@section('metadata')
	<meta name='description' content='startschuss-karriere.de bietet einen Karriereratgeber und informiert über Jobmessen, Karrieremöglichkeiten und den Berufseinstieg.' />
	<meta name='keywords' content='karriereratgeber, jobmessen, berufeinstieg, firmenmesse' />
@stop

@section('content')
	<p>Der Karriere- und Jobmesseratgeber bereitet Sie auf eine erfolgreiche Jobmesse und einen gelungenen Berufseinstieg vor. Er beantwortet Fragen zur Vorbereitung auf die Jobmesse, zum Messebesuch selbst und der Nachbereitung.</p>
	<br>
	
	<?php $count = 0; ?>
	@foreach($featured_articles as $article)
		<div class="span6 {{{ (++$count%2 ? 'alpha' : 'omega') }}}">
			<div class="article">
				<h2 class="article-heading">{{ HTML::link('karriereratgeber/'.$article->slug, $article->title) }}</h2>
				<div class="article-body">{{ Str::limit($article->body , 420) }}</div>
				<div class="pull-right">{{ HTML::link('karriereratgeber/'. $article->slug, 'Mehr lesen', ['class' => 'btn btn-success btn-xs']) }}</div>
			</div>		
		</div>
	@endforeach

	@if($articles->count())
		<div id="more-articles">
			<h1>Weitere Artikel</h1>
		</div>

		<div>
			@foreach(array_chunk($articles->all(), 3) as $row)
				<div class="span12 alpha omega">
				<?php $count = 0 ; ?>
				@foreach($row as $article)
					<?php $class = (++$count%3 ? 'alpha' : 'omega'); ?>
					<?php ($count%3 == 2 ? $class = '' : ''); ?>
					<div class="span4 {{{ $class }}}">
						<div class="article">
							<h2 class="article-heading" title="{{{ $article->title }}}">{{ HTML::link('karriereratgeber/'.$article->slug, Str::limit($article->title, 25)) }}</h2>
							{{ Str::limit($article->body , 240) }}
							<div class="pull-right">{{ HTML::link('karriereratgeber/'. $article->slug, 'Mehr lesen', ['class' => 'btn btn-success btn-xs']) }}</div>
						</div>		
					</div>
				@endforeach
				</div>
			@endforeach
		</div>
	@endif
@stop