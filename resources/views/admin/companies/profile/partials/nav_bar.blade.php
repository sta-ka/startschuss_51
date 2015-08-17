<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/companies/'.$company->id.'/show', 'Profil anzeigen') }}
		</li>
		<li {{ Request::is('*edit-general-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/companies/'.$company->id.'/edit-general-data', 'Stammdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-profile*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/companies/'.$company->id.'/edit-profile', 'Profil bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-contacts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/companies/'.$company->id.'/edit-contacts', 'Kontaktdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-logo*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/companies/'.$company->id.'/edit-logo', 'Logo bearbeiten') }}
		</li>
	</ul>
</div>