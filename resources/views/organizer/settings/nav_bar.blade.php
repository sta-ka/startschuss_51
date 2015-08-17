<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Einstellungen</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/settings/show', 'Übersicht') }}
		</li>
		<li {{ Request::is('*change-password*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/settings/change-password', 'Passwort ändern') }}
		</li>
		<li {{ Request::is('*change-email*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/settings/change-email', 'E-Mail ändern') }}
		</li>
	</ul>
</div>