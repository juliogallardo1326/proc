<?php /* ***** Orca Knowledgebase - German Language File ****** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A small and efficient knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* Installationshinweise finden sie in der Datei "readme.txt" (Englisch)
*
* NOTE: This file contains both polite (suitable for business and
* professional use) and personal (suitable for homepage and hobby
* use) translations.  Change the value of the $polite variable
* below to switch between the translations:
*   true = polite, false = personal
*
* If you translate this file into your native language, please
* send me a copy so I can include it in the forum package.  Your
* name will appear in the header of the file you translate :)
*
* Translated by Christoph Schwarz ( lost-heavens.de )
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("de_DE", "ger"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$polite = false;

$sData['dateformat'] = "%b %d, %Y  %X";  // see http://www.php.net/strftime


if ($polite) {

  /* ***** POLITE *********************************************** */
  /* ***** User GUI ********************************************* */
  /* ************************************************************ */

  $lang['term1'] = "Suchen";
  $lang['term2'] = "L�schen";
  $lang['term3'] = "Zu Frage ID gehen";
  $lang['term4'] = "Alle Kategorien";
  $lang['term5'] = "Alle Unterkategorien";
  $lang['term6'] = "Los";
  $lang['term7'] = "Update";
  $lang['term8'] = "Kategorie";
  $lang['term9'] = "Unterkategorie";
  $lang['terma'] = "Die Frage mit der ID %s existiert nicht";
  $lang['termb'] = "Zur�ck";
  $lang['termc'] = "<strong>%d</strong> Resultat(e)";
  $lang['termd'] = "Suchen in: %s";
  $lang['terme'] = "Zeige an: %s";
  $lang['termf'] = "Frage";
  $lang['termg'] = "Keine Ergebnisse gefunden";
  $lang['termh'] = "Vorheriges";
  $lang['termi'] = "Zeige Fragen %1\$d bis %2\$d";
  $lang['termj'] = "N�chstes";
  $lang['termk'] = "Vorherige Seite";
  $lang['terml'] = "N�chste Seite";
  $lang['termm'] = "Konnten sie nichts finden?";
  $lang['termn'] = "Schicken die ihre Frage an den Knowledgebase Administrator";
  $lang['termo'] = "Ihre Frage";
  $lang['termp'] = "Ihre e-mail Adresse";
  $lang['termq'] = "Danke!";
  $lang['termr'] = "Ihre Frage wurde abgeschickt";
  $lang['terms'] = "L�sung";
  $lang['termt'] = "Filter";


  /* ***** Email ************************************************ */
  $lang['email1']['subject'] = "%s - Frage abgeschickt";
  $lang['email1']['message'] = <<<ORCA
<%1\$s> fragte die folgende Frage via %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


  /* ************************************************************ */
  /* ***** Control Panel **************************************** */
  /* ************************************************************ */
  $lang['misc1'] = "Ordne Liste nach %s";
  $lang['misc2'] = " - Administrationsmen�";
  $lang['misc3'] = "Knowledgebase Administrationsmen�";
  $lang['misc4'] = "Sie sind nicht eingeloggt";
  $lang['misc5'] = "Einloggen";
  $lang['misc6'] = "Sie sind eingeloggt";
  $lang['misc8'] = "Ausloggen";
  $lang['misc9'] = "Weitermachen mit dem Editieren der Knowledgebase";
  $lang['misca'] = "Zur�ck zum Editier-Modus";
  $lang['miscb'] = "Dateiupload-Manager";
  $lang['miscc'] = "Dateien uploaden";
  $lang['miscd'] = "Es traten Fehler auf";
  $lang['misce'] = "Die vorhergegangene Aktion resultierte in folgendem/n Fehler(n)";
  $lang['miscf'] = "Seite refreshen";
  $lang['miscg'] = "L�sche Fehler";


  /* ***** File Upload Manager ********************************** */
  $lang['misch'] = "Kann auf Dateiverzeichnis nicht zugreifen. Bitte CHMOD'e das Verzeichnis %s mit dem Wert 777 (xrw-xrw-xrw)";
  $lang['misci'] = "Refreshen";
  $lang['miscj'] = "Datei:";
  $lang['misck'] = "Verzeichnis zum Speichern:";
  $lang['miscl'] = "Erlaubte Dateiendungen:";
  $lang['miscm'] = "Limit der Dateigr��e:";
  $lang['miscn'] = "Upload";
  $lang['misco'] = "L�schen";
  $lang['miscp'] = "Dateiname";
  $lang['miscq'] = "Dateityp";
  $lang['miscr'] = "Dateigr��e";
  $lang['miscs'] = "L�schen";
  $lang['misct'] = "Datei l�schen";
  $lang['miscu'] = "Sind sie sicher da� sie diese Datei l�schen willst?";
  $lang['miscv'] = "Zur�ck / Refreshen";


  /* ***** Main Controls **************************************** */
  $lang['miscw'] = "Kategorien";
  $lang['miscx'] = "Kategorie hinzuf�gen";
  $lang['miscy'] = "Hinzuf�gen";
  $lang['miscz'] = "Kategorie zum Editieren ausw�hlen";
  $lang['mis_1'] = "Kategorie w�hlen";
  $lang['mis_2'] = "Nichts";
  $lang['mis_3'] = "Ausw�hlen";
  $lang['mis_4'] = "Kategorie umbenennen";
  $lang['mis_5'] = "Neuer Name";
  $lang['mis_6'] = "Umbenennen";
  $lang['mis_7'] = "Kategorie l�schen";
  $lang['mis_8'] = "L�schen";
  $lang['mis_9'] = "Unterkategorien";
  $lang['mis_a'] = "Unterkategorie hinzuf�gen";
  $lang['mis_b'] = "Unterkategorie zum Editieren ausw�hlen";
  $lang['mis_c'] = "Unterkategorie w�hlen";
  $lang['mis_d'] = "Unterkategorie umbenennen";
  $lang['mis_e'] = "Unterkategorie l�schen";
  $lang['mis_f'] = "Fragen";
  $lang['mis_g'] = "Du hast die Kategorie dieser Frage ge�ndert";
  $lang['mis_h'] = "Um den Editiervorgang abzuschlie�en, w�hlen oder erstellen sie bitte eine neue Unterkategorie f�r diese Frage.";
  $lang['mis_i'] = "Unterkategorie";
  $lang['mis_j'] = "Neue Unterkategorie";
  $lang['mis_k'] = "Editiervorgang abbrechen";
  $lang['mis_l'] = "Abbrechen";
  $lang['mis_m'] = "�nderungen akzeptieren";
  $lang['mis_n'] = "Kategorie";
  $lang['mis_o'] = "Online";
  $lang['mis_p'] = "Frage";
  $lang['mis_q'] = "Antwort<br /><small><em>HTML erlaubt</em></small>";
  $lang['mis_r'] = "W�hle Datei";
  $lang['mis_s'] = "Link hinzuf�gen";
  $lang['mis_t'] = "Bild hinzuf�gen";
  $lang['mis_u'] = "Schl�sselw�rter<br /><small><em>mit Komma trennen</em></small>";
  $lang['mis_v'] = "Vorgang abbrechen";
  $lang['mis_w'] = "Frage ID #";
  $lang['mis_x'] = "Zuletzt geupdated";
  $lang['mis_y'] = "Treffer";
  $lang['mis_z'] = "F�gen sie Fragen in dieser Unterkategorie hinzu";
  $lang['mi__1'] = "F�gen sie Fragen in dieser Kategorie hinzu";
  $lang['mi__2'] = "L�schen sie die Frage mit der ID #";
  $lang['mi__3'] = "Editieren sie die Frage mit der ID #";
  $lang['mi__4'] = "Editieren";


  /* ***** Database List Display ******************************** */
  $lang['mi__5'] = "Fragen Datenbank";
  $lang['mi__6'] = "Zeige an:";
  $lang['mi__7'] = "Alle Fragen";
  $lang['mi__8'] = "Frage ID";
  $lang['mi__9'] = "E";	// Short form for Edit


  /* ***** Pagination/Footer ************************************ */
  $lang['mi__a'] = "Vorherige Seite";
  $lang['mi__b'] = "Vorheriges";
  $lang['mi__c'] = "Zeige Fragen %1\$d bis %2\$d";
  $lang['mi__d'] = "N�chste Seite";
  $lang['mi__e'] = "N�chstes";
  $lang['mi__f'] = "** Es gibt keine Fragen in dieser Unterkategorie **";
  $lang['mi__g'] = "** Es gibt keine Fragen in dieser Kategorie **";
  $lang['mi__h'] = "** Es gibt keine Fragen in der Datenbank **";


  /* ***** Error Messages *************************************** */
  $lang['err1'] = "Dateityp nicht erlaubt (%s), oder sie haben keine Datei zum Upload ausgew�hlt";
  $lang['err2'] = "Dateien gr��er als %1\$d BYTES (%2\$d KB) sind nicht erlaubt";
  $lang['err3'] = "Dateiname existiert bereits";
  $lang['err4'] = "Upload fehlgeschlagen.  Sie m�ssen das Zielverzeichnis auf 777 CHMOD'en";
  $lang['err5'] = "Ung�ltige Zeichen im Kategorienamen";
  $lang['err6'] = "Kategorie existiert bereits";
  $lang['err7'] = "Ung�ltige Zeichen im unterkategorienamen";
  $lang['err8'] = "Unterkategorie existiert bereits";
  $lang['err9'] = "Keinen Namen f�r Kategorie angegeben";
  $lang['erra'] = "Frage existiert bereits";
  $lang['errb'] = "Frage ID <strong>%s</strong> exisitiert nicht";
  $lang['errc'] = "Keinen Namen f�r Unterkategorie angegeben";
  $lang['errd'] = "Keine Frage eingegeben";
  $lang['erre'] = "Keine Antwort eingegeben";

} else {

  /* ***** PERSONAL ********************************************* */
  /* ***** User GUI ********************************************* */
  /* ************************************************************ */

  $lang['term1'] = "Suchen";
  $lang['term2'] = "L�schen";
  $lang['term3'] = "Zu Frage ID gehen";
  $lang['term4'] = "Alle Kategorien";
  $lang['term5'] = "Alle Unterkategorien";
  $lang['term6'] = "Los";
  $lang['term7'] = "Update";
  $lang['term8'] = "Kategorie";
  $lang['term9'] = "Unterkategorie";
  $lang['terma'] = "Die Frage mit der ID %s existiert nicht";
  $lang['termb'] = "Zur�ck";
  $lang['termc'] = "<strong>%d</strong> Resultat(e)";
  $lang['termd'] = "Suchen in: %s";
  $lang['terme'] = "Zeige an: %s";
  $lang['termf'] = "Frage";
  $lang['termg'] = "Keine Ergebnisse gefunden";
  $lang['termh'] = "Vorheriges";
  $lang['termi'] = "Zeige Fragen %1\$d bis %2\$d";
  $lang['termj'] = "N�chstes";
  $lang['termk'] = "Vorherige Seite";
  $lang['terml'] = "N�chste Seite";
  $lang['termm'] = "Konntest du nichts finden?";
  $lang['termn'] = "Schicke deine Frage an den Knowledgebase Administrator";
  $lang['termo'] = "Deine Frage";
  $lang['termp'] = "Deine e-mail Adresse";
  $lang['termq'] = "Danke!";
  $lang['termr'] = "Deine Frage wurde abgeschickt";
  $lang['terms'] = "L�sung";
  $lang['termt'] = "Filter";


  /* ***** Email ************************************************ */
  $lang['email1']['subject'] = "%s - Frage abgeschickt";
  $lang['email1']['message'] = <<<ORCA
<%1\$s> fragte die folgende Frage via %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


  /* ************************************************************ */
  /* ***** Control Panel **************************************** */
  /* ************************************************************ */
  $lang['misc1'] = "Ordne Liste nach %s";
  $lang['misc2'] = " - Administrationsmen�";
  $lang['misc3'] = "Knowledgebase Administrationsmen�";
  $lang['misc4'] = "Du bist nicht eingeloggt";
  $lang['misc5'] = "Einloggen";
  $lang['misc6'] = "Du bist eingeloggt";
  $lang['misc8'] = "Ausloggen";
  $lang['misc9'] = "Weitermachen mit dem Editieren der Knowledgebase";
  $lang['misca'] = "Zur�ck zum Editier-Modus";
  $lang['miscb'] = "Dateiupload-Manager";
  $lang['miscc'] = "Dateien uploaden";
  $lang['miscd'] = "Es traten Fehler auf";
  $lang['misce'] = "Die vorhergegangene Aktion resultierte in folgendem/n Fehler(n)";
  $lang['miscf'] = "Seite refreshen";
  $lang['miscg'] = "L�sche Fehler";


  /* ***** File Upload Manager ********************************** */
  $lang['misch'] = "Kann auf Dateiverzeichnis nicht zugreifen. Bitte CHMOD'e das Verzeichnis %s mit dem Wert 777 (xrw-xrw-xrw)";
  $lang['misci'] = "Refreshen";
  $lang['miscj'] = "Datei:";
  $lang['misck'] = "Verzeichnis zum Speichern:";
  $lang['miscl'] = "Erlaubte Dateiendungen:";
  $lang['miscm'] = "Limit der Dateigr��e:";
  $lang['miscn'] = "Upload";
  $lang['misco'] = "L�schen";
  $lang['miscp'] = "Dateiname";
  $lang['miscq'] = "Dateityp";
  $lang['miscr'] = "Dateigr��e";
  $lang['miscs'] = "L�schen";
  $lang['misct'] = "Datei l�schen";
  $lang['miscu'] = "Bist du sicher da� du diese Datei l�schen willst?";
  $lang['miscv'] = "Zur�ck / Refreshen";


  /* ***** Main Controls **************************************** */
  $lang['miscw'] = "Kategorien";
  $lang['miscx'] = "Kategorie hinzuf�gen";
  $lang['miscy'] = "Hinzuf�gen";
  $lang['miscz'] = "Kategorie zum Editieren ausw�hlen";
  $lang['mis_1'] = "Kategorie w�hlen";
  $lang['mis_2'] = "Nichts";
  $lang['mis_3'] = "Ausw�hlen";
  $lang['mis_4'] = "Kategorie umbenennen";
  $lang['mis_5'] = "Neuer Name";
  $lang['mis_6'] = "Umbenennen";
  $lang['mis_7'] = "Kategorie l�schen";
  $lang['mis_8'] = "L�schen";
  $lang['mis_9'] = "Unterkategorien";
  $lang['mis_a'] = "Unterkategorie hinzuf�gen";
  $lang['mis_b'] = "Unterkategorie zum Editieren ausw�hlen";
  $lang['mis_c'] = "Unterkategorie w�hlen";
  $lang['mis_d'] = "Unterkategorie umbenennen";
  $lang['mis_e'] = "Unterkategorie l�schen";
  $lang['mis_f'] = "Fragen";
  $lang['mis_g'] = "Du hast die Kategorie dieser Frage ge�ndert";
  $lang['mis_h'] = "Um den Editiervorgang abzuschlie�en, w�hle oder erstelle bitte eine neue Unterkategorie f�r diese Frage.";
  $lang['mis_i'] = "Unterkategorie";
  $lang['mis_j'] = "Neue Unterkategorie";
  $lang['mis_k'] = "Editiervorgang abbrechen";
  $lang['mis_l'] = "Abbrechen";
  $lang['mis_m'] = "�nderungen akzeptieren";
  $lang['mis_n'] = "Kategorie";
  $lang['mis_o'] = "Online";
  $lang['mis_p'] = "Frage";
  $lang['mis_q'] = "Antwort<br /><small><em>HTML erlaubt</em></small>";
  $lang['mis_r'] = "W�hle Datei";
  $lang['mis_s'] = "Link hinzuf�gen";
  $lang['mis_t'] = "Bild hinzuf�gen";
  $lang['mis_u'] = "Schl�sselw�rter<br /><small><em>mit Komma trennen</em></small>";
  $lang['mis_v'] = "Vorgang abbrechen";
  $lang['mis_w'] = "Frage ID #";
  $lang['mis_x'] = "Zuletzt geupdated";
  $lang['mis_y'] = "Treffer";
  $lang['mis_z'] = "F�ge Fragen in dieser Unterkategorie hinzu";
  $lang['mi__1'] = "F�ge Fragen in dieser Kategorie hinzu";
  $lang['mi__2'] = "L�sche Frage mit der ID #";
  $lang['mi__3'] = "Editiere Frage mit der ID #";
  $lang['mi__4'] = "Editieren";


  /* ***** Database List Display ******************************** */
  $lang['mi__5'] = "Fragen Datenbank";
  $lang['mi__6'] = "Zeige an:";
  $lang['mi__7'] = "Alle Fragen";
  $lang['mi__8'] = "Frage ID";
  $lang['mi__9'] = "E";	// Short form for Edit


  /* ***** Pagination/Footer ************************************ */
  $lang['mi__a'] = "Vorherige Seite";
  $lang['mi__b'] = "Vorheriges";
  $lang['mi__c'] = "Zeige Fragen %1\$d bis %2\$d";
  $lang['mi__d'] = "N�chste Seite";
  $lang['mi__e'] = "N�chstes";
  $lang['mi__f'] = "** Es gibt keine Fragen in dieser Unterkategorie **";
  $lang['mi__g'] = "** Es gibt keine Fragen in dieser Kategorie **";
  $lang['mi__h'] = "** Es gibt keine Fragen in der Datenbank **";


  /* ***** Error Messages *************************************** */
  $lang['err1'] = "Dateityp nicht erlaubt (%s), oder du hast keine Datei zum Upload ausgew�hlt";
  $lang['err2'] = "Dateien gr��er als %1\$d BYTES (%2\$d KB) sind nicht erlaubt";
  $lang['err3'] = "Dateiname existiert bereits";
  $lang['err4'] = "Upload fehlgeschlagen.  Du mu�t das Zielverzeichnis auf 777 CHMOD'en";
  $lang['err5'] = "Ung�ltige Zeichen im Kategorienamen";
  $lang['err6'] = "Kategorie existiert bereits";
  $lang['err7'] = "Ung�ltige Zeichen im unterkategorienamen";
  $lang['err8'] = "Unterkategorie existiert bereits";
  $lang['err9'] = "Keinen Namen f�r Kategorie angegeben";
  $lang['erra'] = "Frage existiert bereits";
  $lang['errb'] = "Frage ID <strong>%s</strong> exisitiert nicht";
  $lang['errc'] = "Keinen Namen f�r Unterkategorie angegeben";
  $lang['errd'] = "Keine Frage eingegeben";
  $lang['erre'] = "Keine Antwort eingegeben";
}

while (list($key, $value) = each($lang)) {
  if (!is_array($value) && $key != "charset") {
    if ($pageEncoding == 3) {
      $lang[$key] = htmlentities($value, ENT_COMPAT, "ISO-8859-1");
      $lang[$key] = str_replace("&gt;", ">", $lang[$key]);
      $lang[$key] = str_replace("&lt;", "<", $lang[$key]);
    } else if ($pageEncoding == 1) $lang[$key] = utf8_encode($value);
  }
}

?>