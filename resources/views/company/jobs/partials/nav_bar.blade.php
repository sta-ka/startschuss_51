<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Jobs</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('company/jobs/'.$job->id.'/show', 'Übersicht') }}
		</li>
	</ul>
</div>