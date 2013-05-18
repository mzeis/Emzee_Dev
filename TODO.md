TODO
====

 * Ganz oben: hinschreiben, von wo aus aufgerufen wurde
 * Grüne Code-Zeile klickbar -> darunter klappt sich die Info zu der Zeile aus (welche Klasse, welche Methode aufgerufen, welche Argumente)
 * CSS-Klassen überprüfen (debug-container etc.)
 * Manchmal erfolgt der Aufruf so früh, dass PrototypeJS noch nicht eingebunden ist. In diesen Fällen erkennen und selbst einbinden (Suche in Standard-Pfad von Prototype)?
 * Refactoring: $id zu Elementen (Überschriften) etc. hinzufügen
 * Weitere Infos zu den Methoden (Docblock, Parameter etc.)
 * Links zu den einzelnen Untersektionen (Tabs?)
 * Filtern der angezeigten Methoden nach Klasse. Sortieren der Methoden nach Klasse.
 * Vergrößern/Verkleinern des Elements
 * Falls Dev-Toolbar vorhanden ist: Debug-Ausgabe "verstecken" und einen Link in der Toolbar anbieten?
 * Debug backtrace:
   - Information $dbgLine['object'] verarbeiten
   - String aus function/class/type zusammenbasteln
   - Wert in $dbgLineArgVal verarbeiten
   - Farbcodierung der Zeilen je nach dem Teil von MVC, aus dem der Aufruf stammt? 