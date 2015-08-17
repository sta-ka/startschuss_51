<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/show', 'Profil anzeigen') }}
		</li>
		<li {{ Request::is('*edit-basics*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/edit-basics', 'Profil bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-program*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/edit-program', 'Programm bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-contacts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/edit-contacts', 'Kontaktdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-logo*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/edit-logo', 'Logo bearbeiten') }}
		</li>
		<li><h3>Teilnehmer</h3></li>
		<li {{ Request::is('*manage-participants*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/manage-participants', 'Teilnehmer verwalten') }}
		</li>
		@if($event->interviews)
		<li {{ Request::is('*manage-interviews*')  ? 'class="active"' : '' }} >
			{{ HTML::link('organizer/profile/'.$event->id.'/manage-interviews', 'Einzelgespräche verwalten') }}
		</li>
		@endif
	</ul>
</div>