<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Statistik</h3></li>
		</li>
		<li {{ Request::is('*logged-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/dashboard/logged-data', 'Ereignisse') }}
		<li {{ Request::is('*requested-events*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/dashboard/requested-events', 'Neue Messen') }}
		</li>
	</ul>
</div>