# Backlog
## Sprint 1 (14.9-25.9)
### Planning
- Erstellen Zeitplan
- Repo anlegen
- Recherche, was für Frameworks und Arbeiten gibt es bereits
- Tasks für Sprint 2 definieren

### Review
#### Signatur erkennen
Machine Learning basierter Ansatz
https://medium.com/snapaddy-tech-blog/machine-learning-for-signature-detection-7c62d838f520


Python Code, auch machine learning basiert (Dokumentation leider auf russisch)
https://github.com/Dimama/email-signature-detection

Open Source Framework by Mailgun
https://www.mailgun.com/blog/open-sourcing-our-email-signature-parsing-library/
https://github.com/mailgun/talon


Research Papers
https://www.researchgate.net/publication/221650827_Learning_to_Extract_Signature_and_Reply_Lines_from_Email
https://library.ndsu.edu/ir/bitstream/handle/10365/25893/Signature%20Extraction%20from%20E-Mails.pdf?sequence=1&isAllowed=y
http://keg.cs.tsinghua.edu.cn/jietang/publications/f142-tang.pdf
https://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.11.2718
http://www.cs.cmu.edu/~vitor/papers/sigFilePaper_finalversion.pdf


#### Web Scraping
Im Unterricht behandelt
https://scrapy.org/

Privat benutzt
https://www.crummy.com/software/BeautifulSoup/bs4/doc/
https://mechanicalsoup.readthedocs.io/en/stable/


weitere Webcrawler
http://stormcrawler.net/
https://www.norconex.com/
http://go-colly.org/


#### Extracting Contact Information
https://papers.ssrn.com/sol3/papers.cfm?abstract_id=3358293
https://datascienceplus.com/how-to-extract-email-phone-number-from-a-business-card-using-python-opencv-and-tesseractocr/
https://medium.com/@acrosson/extracting-names-emails-and-phone-numbers-5d576354baa

### Retro
- investierte Zeit 10h/10h
- geplant 18h/144h 12.5%
- Guter Einstieg in die Bachelorarbeit
- Viel research, viele Paper sind sehr komplex.
- Viel zu wenig Zeit investiert
- Tempo und Disziplin noch massiv steigern um das Ziel zu erreichen

## Sprint 2 (28.9-9.10)

- Als Entwickler kann ich dem System den Inhalt einer E-Mail übergeben. Das System erkennt nun den die Signatur, sodass ich diese in einem weiteren Schritt auswerten kann.
  - Email Header parsen (RFC 5332)
  - Beschaffen und anotieren von E-Maildaten aus CRM von Arbeitgeber
  - Machinelearning basierter Ansatz probieren
  - Heuristischer Ansatz probieren mittels Namenserkennung
  - Vergleich der beiden Varianten
  - Erkennen von Email Repleys um nicht alte Signaturen zu parsen
  - Implementation einer API, die eine E-Mail entgegen nimmt und die Metadaten zurück gibt (From, To, Date, Body, Signature)
- Kapitel Methodik
- Tasks für Sprint 3 definieren

### Retro
- investierte Zeit 26h/36h
- geplant Woche 36h/144h 25%
- Abklährung in welchem Format erhalte ich die E-Mails 'plain/text', 'plain/html' oder beides .eml file
- Sprachen wirrwar PHP vs. Python Entscheid wie API schreiben
- keine Zeit füt Maschinlearning Ansatz -> Werde ich nachholen in Sprint 3
- Sprint 3 etwas weniger voll laden, da noch Left overs


## Sprint 3 (12.10-23.10)

- Left Over Sprint 2
  - Machine Learning basierter Ansatz

- Als User kann ich dem System den Inhalt einer E-Mail übergeben und erhalte danach die Signatur aufgeschlüsselt in ihre Bestandteile, sodass diese automatisch im CRM System ergänzt oder geupdatet werden kann.
  - Anotieren der Signaturen um das System zu validieren
  - Klähren, in welchem Format erhalte ich die E-Mails
    - Repleys erkennen, kann erst gemacht werden wenn Format geklährt.
  - E-Mail und Telefonnummer erkennen mittels regex
  - Name erkennen mittels arbeit Projekt 2
  - Analyse wie Strasse, Ort erkennen.
- Einrichten Serverless für Python API

### Retro
- investierte Zeit 19h/55h
- geplant Zeit 54h/144h 37.5%
- Maschinlearning bassierter mittels Simpletree hat eine sehr einfache Lösung ergeben
- E-Mails kommen als Rein Text
- Das System kann bereits filtern ob es sich um ein Replay handelt, darum Replay detection nicht nötig.
- Fax wird aktuell noch als Tel angezeigt
- Schwirigkeit macht akutell noch der Jobtitel


## Sprint 4 (26.10-6.11)

- Als Entwickler erhalte ich alle URLs der E-Maildomain, welche den übergebenen Namen enthalten, sodass ich in einem weiteren Schritt diese nach Informationen zur gesuchten Person durchsuchen kann.
  - Allen Links folgen. Evaluieren, wie viele Stufen werden benötigt?
  - Um den Webserver zu schonen, Seiten cachen um Seiten nicht doppelt abzufragen.

### Retro
- investiere Zeit 17h/72h
- geplante Zeit 72h/144h 50%
- Anzahl der Seiten steigt Exponentiell an.
  - Bsp. bfh.ch Startseite 216 Links, 1. Follow >60'000
  - Bsp. Person Jürgen Vogel immer noch nicht gefunden
  - Dauer aller Anfragen > 3min (seriell abgefragt, könnte evtl. noch optimiert werden)
- Google nutzen um die relevanten Seiten zu finden
- Aufbau Lexikon Jobtitel via jobs.ch geplant sind weitere Jobportale


## Sprint 5 (13.11-20.11)

- Als User kann ich dem System einen Namen und eine Domain übergeben und erhalte alle Informationen zu dieser Person, welche sich auf dieser Seite befinden.
  - Parsen der optisch umliegenden Elemente um den gesuchten Namen

### Retro
- investiere Zeit 1h/73h
- geplante Zeit 90h/144h 62.5%

Aufgrund eines grösseren Projektes meines Arbeitgebers konnte ich leider kaum Zeit für die Bachelorarbeit investieren. Deshalb gab es auch keine Resultate in diesem Sprint.
Als Massnahme wurde ein achter Sprint über die Weihnachtsfeiertage eingeplant.

## Sprint 6 (23.11-4.12)

- Als User kann ich dem System einen Namen und eine Domain übergeben und erhalte alle Informationen zu dieser Person, welche sich auf dieser Seite befinden.
  - Parsen der optisch umliegenden Elemente um den gesuchten Namen

### Retro
- investiere Zeit 12h/85h
- geplante Zeit 108h/144h 75%
- Leider ist das Firmenprojekt immer noch nicht ganz abgeschlossen, weshalb nur 12h statt 18h investiert werden konnten. Durch den zusätzlichen Sprint 8, sollte dies aber kompensiert werden können.
- Hauptschwirigkeit ist heruaszufinden, wie weit nach aussen um den Namen gesucht werden muss.

## Sprint 7 (7.12-18.12)

- Als User möchte ich die Qualität des Systemes gegen ein grosses Testset testen, sodass ich Rückschlüsse über die Qualität und Funktionalität haben kann.

Resultat Email Signatur
- 93% Precission (1496 Emails)

Sehr gute Resultate erziehlt. Falls noch genügend Zeit bleibt, wäre es interessant dies direkt in Outlook mittels eines Plugins zu integrieren.

Resultat Web Scaping
- 36% Precission (9860 Kontakte)
Leider gibt es oft über nicht Kadermitglieder keine Information auf der Webseite.
Darum ist dieses Resultat trotz relativ hilfreicht, da es bei den 36% vorallem um Entscheidungsträger geht.

### Retro
- investiere Zeit 9h/97h
- geplante Zeit 126h/144h 87.5%


## extra Sprint 8 (21.12-8.1)

- Kapitel Ergebniss schreiben
- Kapitel Ethik/ Datenschutz schreiben
- Kapitel Theorie
- Fazit schreiben

**Weihnachtsferien**

## Reserve Sprint (11.1-22.1)

- Erstellen Präsentation
- Erstellen Kurzfilm
- Erstellen Poster
- Korrekturlesen
- Layout prüfen
- Arbeit fertig stellen
- (Implementieren zusätzlicher Funktionalitäten)

## Wichtige Daten

- Meeting mit Experte Mi 28.10.2020 10:00
- Meeting mit Experte Mi 7.12.2020 10:15
- Abgabe Book Do. 7.1.2021
- Abgabe Do. 21.1.2021
- Präsentation Fr. 22.1.2021
- Posterausstellung Fr. 22.1.2021
- Verteidigung Mo. 25.1.2021 13:00
- Abgabe Kurzfilm Fr. 29.1.2021
- Verteidigung Mo. 25.1 13:00 oder Di 26.1 09:30
