<div id="footer">
	<div class="container_12">
		<div class="span3 alpha">
			<h5>Navigation</h5>
			<ul class="footer-nav">
				<li class="{{ set_active('home*') }}">
					{{ HTML::linkRoute('home', 'Home') }}
				</li>
				<li class="{{ set_active('jobmesse*') }}">
					{{ HTML::linkRoute('messekalender', 'Messen') }}
				</li>
				<li class="{{ set_active('veranstalter*') }}">
					{{ HTML::linkRoute('veranstalterdatenbank', 'Veranstalter') }}
				</li>
				<li class="{{ set_active('karriereratgeber*') }}">
					{{ HTML::linkRoute('karriereratgeber', 'Ratgeber') }}
				</li>
				<li class="{{ set_active('kontakt*') }}">
					{{ HTML::linkRoute('kontakt', 'Kontakt') }}
				</li>
				<li class="{{ set_active('impressum*') }}">
					{{ HTML::linkRoute('impressum', 'Impressum') }}
				</li>
			</ul>
		</div>
		<div class="span9 omega">
			<p><strong>startschuss-karriere.de</strong> bietet einen Überblick über aktuelle Job- und Karrieremessen in Deutschland, Österreich und der Schweiz. 
			Im Jobmessekalender finden Sie eine Übersicht zu den nächsten Jobmessen und umfangreiche Informationen zu den einzelnen Jobmessen, ihren Veranstaltern und den teilnehmenden Unternehmen.</p>
			<p>Mit <strong>startschuss-karriere.de</strong> knüpfen Sie Kontakte auf Jobmessen und starten Ihre Karriere.</p>
		</div>
	</div>
</div>