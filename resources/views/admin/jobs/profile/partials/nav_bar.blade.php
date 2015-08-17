<div id="nav-bar">
	<ul class="nav nav-pills nav-stacked">
		<li><h3>Profil</h3></li>
		<li {{ Request::is('*show*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/jobs/'.$job->id.'/show', 'Stellenangebot anzeigen') }}
		</li>
		<li {{ Request::is('*edit-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/jobs/'.$job->id.'/edit-data', 'Daten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-seo-data*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/jobs/'.$job->id.'/edit-seo-data', 'Metadaten bearbeiten') }}
		</li>
		<li {{ Request::is('*edit-settings*')  ? 'class="active"' : '' }} >
			{{ HTML::link('admin/jobs/'.$job->id.'/edit-settings', 'Einstellungen bearbeiten') }}
		</li>
	</ul>
</div>