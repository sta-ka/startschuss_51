<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('company/profile/'.$company->id.'/show', 'Profil anzeigen') }}
		</li>
		<li {{ Request::is('*edit-basics*')  ? 'class="active"' : '' }} >
			{{ HTML::link('company/profile/'.$company->id.'/edit-basics', 'Profil bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-contacts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('company/profile/'.$company->id.'/edit-contacts', 'Kontaktdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-logo*')  ? 'class="active"' : '' }} >
			{{ HTML::link('company/profile/'.$company->id.'/edit-logo', 'Logo bearbeiten') }}
		</li>
	</ul>
</div>