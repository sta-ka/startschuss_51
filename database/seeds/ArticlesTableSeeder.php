<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('articles')->insert(
            array(
                array(
                    'title'            => 'Vorbereitung auf die Jobmesse',
                    'slug'             => 'vorbereitung-auf-die-jobmesse',
                    'body'             => '<p>Jobmessen bieten eine ideale Gelegenheit Kontakt zu Unternehmen zu knüpfen und potenzielle Arbeitgeber kennen zu lernen. Doch bevor Sie die Messehalle betreten, sollten Sie sich gründlich auf Ihren Messebesuch vorbereiten. An erster Stelle steht die Auswahl einer Jobmesse. Verschaffen Sie sich einen Überblick über Jobmessen, die in ihrer Region stattfinden, und informieren Sie sich über die Ausrichtung und Zielgruppen dieser Jobmessen. In unserem Jobmessekalender können Sie gezielt nach Jobmessen in Ihrer Region suchen.</p><p>Besuchen Sie eine Jobmesse möglichst schon vor Abschluss ihres Studiums. Auch im Laufe Ihres Studiums lohnt sich ein Besuch einer Jobmesse, um frühzeitig Kontakte zu Unternehmen zu knüpfen oder sich über Praktika oder Abschlussarbeiten zu informieren. Auch Bewerbungsberatungen oder ein Bewerbungsmappencheck sind sinnvolle Angebote für Studenten auf Jobmessen, um sich für Ihre weiteren Karriereschritte vorzubereiten. Im Messekatalog oder auf dem Internetauftritt der Jobmesse finden Sie eine Übersicht über die teilnehmenden Unternehmen. Sammeln Sie Informationen über für Sie interessante Unternehmen und sichten Sie die Jobangebote dieser Unternehmen. Zusätzlich gibt es auf vielen Jobmessen eine “Jobwall”, auf der Unternehmen offene Stellen aushängen. Legen Sie sich Fragen zu Recht, die sie im Gespräch mit einem Unternehmensvertreter klären wollen. Dies zeigt ihrem Gesprächspartner zudem, dass Sie sich mit dem Unternehmen auseinandergesetzt haben. Erstellen Sie eine Auswahl von 5 bis 10 Unternehmen, die Sie auf der Messe besuchen wollen. Planen Sie pro Gespräch 30 bis 45 Minuten ein.</p><p>Auf großen Jobmessen mit über 100 Unternehmen ist auch ein Messeplan recht hilfreich. Setzten Sie ihre favorisieren Unternehmen nicht an erste Stelle, sondern besser an dritte, um in den ersten Gesprächen üben zu können.</p><p>Gibt es die Möglichkeit mit den Personalern schon vor der Messe in Kontakt in Kontakt zu treten und einen Termin zu vereinbaren, sollten Sie diese unbedingt nutzen. Termine vormittags sind hierbei zu bevorzugen, da sind Sie wie auch die Unternehmensvertreter noch weniger gestresst. Das wichtigste Dokument für Ihren Messebesuch ist die Bewerbungsmappe. Neben einem kurzen Anschreiben gehört dazu Ihr Lebenslauf und ein aktuelles Bewerbungsfoto. Für die im Vorfeld der Messe von Ihnen ausgewählten Unternehmen erstellen Sie ein individuelles Anschreiben. Fast alle Jobmessen bieten ein Rahmenprogramm bestehend aus Vorträgen und Workshops. Vorträge bieten interessante Möglichkeitein mehr über ausgewählte Unternehmen zu erfahren und mit Unternehmensvertretern in Kontakt zu treten.</p><p>Für das Outfit auf einer Jobmesse gelten die selben Regeln wie beim Vorstellungsgespräch. Denn auch hier zählt der erste Eindruck. Sorgen Sie für einen entspannte Anreise. Planen Sie genug Zeit für die Anreise ein, um mögliche Verzögerungen bei der Anreise ausgleichen zu können.</p>',
                    'active'           => 1,
                    'featured'         => 1,
                    'meta_description' => 'Hilfreiche Tipps zur Vorbereitung Ihres Besuchs einer Jobmesse.',
                    'keywords'         => '',
                    'created_at'       => new DateTime,
                    'updated_at'       => new DateTime
                ),
                array(
                    'title'            => 'Tipps für den Besuch einer Jobmesse',
                    'slug'             => 'tipps-für-den-besuch-einer-jobmesse',
                    'body'             => '<p>Damit sich der Besuch einer Jobmesse lohnt, gibt es einiges zu beachten. Ihr Verhalten gegenüber anderen Messeteilnehmern und Personen am Stand gibt Aufschluss über Ihre Sozialkometenz. Vermeiden Sie Fragen, die Sie auch vorab per simpler Recherche hätten klären können. Mit einer gründlichen Vorbereitung und Wissen über das Unternehmen, seine Produkte und die Unternemensphilopsophie können Sie im Gespräch punkten.</p><p>Zusätzlich können Sie sich auch einen knappen Überblick über die aktuelle Berichterstattung zum Unternehmen machen und dies im Gespräch einstreuen. Fragen über vakante Stellen, das Bewerbungsverfahren, Karrieremöglichkeiten oder wichtige Zusatzqualifikationen bieten sich an um im persönlichen Gespräch geklärt zu werden.Nutzen Sie das Rahmenprogramm der Jobmesse aus: Bei Unternehmenspräsentationen, Workshops oder Vorträgen können Sie mit Unternehmensvertretern ins Gespräch kommen oder neue Unternehmen kennenlernen.</p><p>Störend kann auch zu viel Gepäck sein. Eine handliche Aktentasche oder eine Ledermappe mit Ihren Bewerbungsunterlagen sollte ausreichen als Gepäck. Zum Abschluss des Gesprächs fragen Sie Ihren Gesprächspartner nach einer Visitenkarte. Machen Sie sich Notizen über den Gesprächsverlauf und mögliche, neue Informationen.</p>',
                    'active'           => 1,
                    'featured'         => 1,
                    'meta_description' => 'Tipps rund um den Besuch einer Jobmesse. Was gibt es zu beachten, was ist wichtig?',
                    'keywords'         => '',
                    'created_at'       => new DateTime,
                    'updated_at'       => new DateTime
                ),
                array(
                    'title'            => 'Karriere im Mittelstand',
                    'slug'             => 'karriere-im-mittelstand',
                    'body'             => '<p>Bei vielen Absolventen stehen die großen Unternehmen und Konzerne an erster Stelle bei ihrer Karriereplanung. Diese Unternehmen haben einen wesentlich höheren Bekanntheitsgrad als die Mittelständler. Dies liegt zum einen daran, dass Mittelständler bei deutlich weniger Job- und Karrieremessen vertreten sind und auch das Hochschulmarketing bei weitem nicht so stark ist wie bei den großen Unternehmen. Hinzu kommt, dass Mittelständler oftmals in ländlicheren Regionen angesiedelt sind.</p><p>Doch viele Mittelständler spielen in ihren Märkten eine gewichtige Rolle. Sie konzentrieren sich auf Nischenmärkte und sind in diesen oftmals Martfährer, daher kommt auch der der Begriff der „Hidden Champions“. Sowohl in der Industrie als auch im Dienstleistungssektor gibt es eine große Zahl an erfolgreichen Mittelständlern.</p><p>Nach der Definiton der EU gehören Unternehmen mit 50 bis zu 250 Mitarbeitern und einen Geschäftsvolumen von unter 50 Millionen Euro zu den mittleren Unternehmen.  In Deutschland beschäftigt der Mittelstand über 20 Millionen Arbeitnehmer, darunter rund 600.000 Führungskräfte.</p><p>Mittelständler zeichnen sich durch flache Hierarchien, kurze Entscheidungswege und oftmals auch international ausgerichtete Aufgaben aus. Sie suchen eher Generalisten, die besonders in der Arbeit im Team ihre Stärken haben. Ein weiterer Vorteil eines Mittelständlers ist die Möglichkeit eines schnelleren, beruflichen Aufstiegs. Der eigene Beitrag zum Erfolg des Unternehmens ist wesentlich sichtbarer als im Konzern.</p><p>Um Mittelständler zu finden eignen sich Suchmaschinen im Netz, die IHK, Branchen- und Berufsverbände sowie der Besuch von Karriere- und Jobmessen.</p><p>Allerdings sind systematische Trainee-Programme bei kleineren Unternehmen bei weitem nicht so üblich wie bei großen Unternehmen. Der Direkteinstieg mit z. B. einem Mentorenmodell ist eher üblich. Der Einstieg im Mittelstand bedeutet meist kurze Einarbeitungsphasen und ein breiteres Aufgabenspektrum. Da viele der „Hidden Champions“ auch international tätig sind, sind Auslandseinsätze auch durchaus möglich.</p>',
                    'active'           => 1,
                    'featured'         => 0,
                    'meta_description' => 'Viele Absolventen denken beim Berufseinstieg an die Big Player, dabei hat der Mittelstand auch einiges zu bieten.',
                    'keywords'         => '',
                    'created_at'       => new DateTime,
                    'updated_at'       => new DateTime
                )
            )
        );
    }
}