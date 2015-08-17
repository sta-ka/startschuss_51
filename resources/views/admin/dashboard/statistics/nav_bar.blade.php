<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Statistik</h3></li>
		<li {{ Request::is('*logins*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/dashboard/logins', 'Logins') }}
		<li {{ Request::is('*login-attempts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/dashboard/login-attempts', 'Loginversuche') }}
		</li>
	</ul>
</div>