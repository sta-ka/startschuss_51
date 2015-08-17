<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/show', 'Übersicht') }}
		</li>
		<li {{ Request::is('*edit-general-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-general-data', 'Stammdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-profile*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-profile', 'Profil bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-program*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-program', 'Programm bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-contacts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-contacts', 'Kontaktdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-seo-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-seo-data', 'Metadaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-logo*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/edit-logo', 'Logo bearbeiten') }}
		</li>
		<li><h3>Teilnehmer</h3></li>
		<li {{ Request::is('*manage-participants*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/manage-participants', 'Teilnehmer verwalten') }}
		</li>
		
		@if($event->interviews)
		<li {{ Request::is('*manage-interviews*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/manage-interviews', 'Einzelgespräche verwalten') }}
		</li>
		<li {{ Request::is('*event-interviews*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/events/'.$event->id.'/event-interviews', 'Einzelgespräche anzeigen') }}
		</li>
		@endif
	</ul>
</div>