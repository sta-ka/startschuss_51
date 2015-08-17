<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/users/'.$user->id.'/show', 'Übersicht') }}
		</li>
		<li {{ Request::is('*edit*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/users/'.$user->id.'/edit', 'Nutzer bearbeiten') }}
		</li>
		<li {{ Request::is('*send-mail*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/users/'.$user->id.'/send-mail', 'Nutzer kontaktieren') }}
		</li>
		<li {{ Request::is('*statistics*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/users/'.$user->id.'/statistics', 'Statistik') }}
		</li>
	</ul>
</div>