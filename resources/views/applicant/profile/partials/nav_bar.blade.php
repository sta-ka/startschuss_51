<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/show', 'Profil anzeigen') }}
		</li>
		<li {{ Request::is('*edit-basics*')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/edit-basics', 'Profil bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-contacts*')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/edit-contacts', 'Kontaktdaten bearbeiten') }}
		</li>
		<li {{ Request::is('*education*')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/show-education', 'Ausbildung') }}
		</li>
		<li {{ Request::is('*experience*')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/show-experience', 'Berufserfahrung') }}
		</li>
		<li {{ Request::is('*edit-photo*')  ? 'class="active"' : '' }} >
			{{ HTML::link('applicant/profile/edit-photo', 'Foto bearbeiten') }}
		</li>
	</ul>
</div>