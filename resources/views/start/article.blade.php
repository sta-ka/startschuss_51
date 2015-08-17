@extends('layouts.default')

@section('title', 'Der Karriere- und Jobmesse-Ratgeber')

@section('metadata')
	<meta name='description' content='{{{ $article->meta_description }}}' />
	<meta name='keywords' content='{{{ $article->keywords }}}' />
@stop

@section('content')
	<div>
		<div class="span8 alpha" id="article">
			<h2 class="article-heading">{{{ $article->title }}}</h2>
			{{ $article->body }}
		</div>
		<div class="span4 omega" id="article-teaser">
			<h2>Beliebte Artikel</h2>
				@foreach($articles as $article)
				<div class="article-preview">
					<h4 class="article-heading">{{ HTML::link('karriereratgeber/'.$article->slug, Str::limit($article->title, 35)) }}</h4>
					<div class="article">{{ Str::limit($article->body, 120) }}</div>
					<div class="pull-right">{{ HTML::link('karriereratgeber/'.$article->slug, 'Mehr lesen', ['class' => 'btn btn-success btn-xs']) }}</div>
				</div>
			@endforeach
			{{ HTML::link('karriereratgeber', 'Zur Artikelübersicht', ['class' => 'btn btn-primary btn-block']) }}
		</div>
	</div>

@stop