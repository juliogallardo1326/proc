<?php /* ***** Orca Knowledgebase - Dutch Language File ******* */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A small and efficient knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*
* Translation by J.Tiggelman ( www.trains-sim-depot.nl )
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("en_EN", "enc"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$sData['dateformat'] = "%b %d, %Y  %X";  // see http://www.php.net/strftime


/* ************************************************************ */
/* ***** User GUI ********************************************* */
/* ************************************************************ */

$lang['term1'] = "Zoeken";
$lang['term2'] = "Wissen";
$lang['term3'] = "Naar vraag NR";
$lang['term4'] = "Alle Categories";
$lang['term5'] = "Alle Subcategories";
$lang['term6'] = "Ga";
$lang['term7'] = "Bijgewerkt";
$lang['term8'] = "Categorie";
$lang['term9'] = "Subcategorie";
$lang['terma'] = "Vraag NR %s bestaat niet";
$lang['termb'] = "Terug";
$lang['termc'] = "<strong>%d</strong> Resultaten";
$lang['termd'] = "Zoeken in: %s";
$lang['terme'] = "Weergeven: %s";
$lang['termf'] = "Zoek opdracht";
$lang['termg'] = "Geen resultaten gevonden";
$lang['termh'] = "Vorige";
$lang['termi'] = "Weergave onderwerpen %1\$d tot %2\$d";
$lang['termj'] = "Volgende";
$lang['termk'] = "Vorige pagina";
$lang['terml'] = "Volgende pagina";
$lang['termm'] = "Geen record gevonden?";
$lang['termn'] = "Stel uw vraag aan de beheerder";
$lang['termo'] = "Uw vraag";
$lang['termp'] = "Uw e-mail adres";
$lang['termq'] = "Bedankt!";
$lang['termr'] = "Uw vraag is verstuurd";
$lang['terms'] = "Record";
$lang['termt'] = "Filter";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Verstuurde vraag";
$lang['email1']['message'] = <<<ORCA
<%1\$s> stel de volgende vragen via %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Lijst sorteren op %s";
$lang['misc2'] = " - Controle Paneel";
$lang['misc3'] = "Kennisbank Controle Paneel";
$lang['misc4'] = "U bent niet aangemeld";
$lang['misc5'] = "Aanmelden";
$lang['misc6'] = "U bent aangemeld";
$lang['misc8'] = "Afmelden";
$lang['misc9'] = "Verdergaan met bewerken van de kennisbank";
$lang['misca'] = "Terug naar bewerk modus";
$lang['miscb'] = "Bestanden upload manager";
$lang['miscc'] = "Upload bestand(en)";
$lang['miscd'] = "Er deden zich fouten voor";
$lang['misce'] = "De voorgaande actie heeft de volgende fout(en) opgeleverd:";
$lang['miscf'] = "pagina verversen";
$lang['miscg'] = "Fout verwijderen";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "De bestanden directorie is niet te gebruiken. zorg ervoor dat deze door dit script is te besschrijven %s met deze opdracht cmod 777";
$lang['misci'] = "Verversen";
$lang['miscj'] = "Bestand:";
$lang['misck'] = "Opslag directorie:";
$lang['miscl'] = "Bestanden die zijn toegestaan:";
$lang['miscm'] = "Bestand limiet (byte's):";
$lang['miscn'] = "Upload";
$lang['misco'] = "Wisse";
$lang['miscp'] = "Bestandnaam";
$lang['miscq'] = "Bestand type";
$lang['miscr'] = "Bestand grootte";
$lang['miscs'] = "Verwijderen";
$lang['misct'] = "Bestand verwijderen";
$lang['miscu'] = "Weet u zeker dat u dit bestand wilt verwijderen?";
$lang['miscv'] = "Terug / Verversen";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Categorie Controle";
$lang['miscx'] = "Categorie toevoegen";
$lang['miscy'] = "Toevoegen";
$lang['miscz'] = "Kies werk categorie";
$lang['mis_1'] = "Kies een categorie";
$lang['mis_2'] = "Geen";
$lang['mis_3'] = "Kies";
$lang['mis_4'] = "Categorienaam wijzigen";
$lang['mis_5'] = "Nieuwe naam";
$lang['mis_6'] = "Hernoemen";
$lang['mis_7'] = "Verwijder categorie";
$lang['mis_8'] = "Verwijderen";
$lang['mis_9'] = "Subcategorie Controle";
$lang['mis_a'] = "Categorie toevoegen";
$lang['mis_b'] = "Kies werk subcategorie";
$lang['mis_c'] = "Kies een subcategorie";
$lang['mis_d'] = "Subcategorienaam wijzigen";
$lang['mis_e'] = "Verwijder subcategorie";
$lang['mis_f'] = "Records Controle";
$lang['mis_g'] = "U heeft de categorie van dit record veranderd";
$lang['mis_h'] = "Om deze bewerking te voltooien moet u een categorie aanmaken of selecteren voor deze vraag";
$lang['mis_i'] = "Subcategorie";
$lang['mis_j'] = "Nieuwe Subcategorie";
$lang['mis_k'] = "Record bewerken anuleren";
$lang['mis_l'] = "Anuleren";
$lang['mis_m'] = "Bewerking is klaar";
$lang['mis_n'] = "Categorie";
$lang['mis_o'] = "Publiseren";
$lang['mis_p'] = "Vraag";
$lang['mis_q'] = "Record<br /><small><em>HTML is toegestaan</em></small>";
$lang['mis_r'] = "Selecteer bestand";
$lang['mis_s'] = "Link toevoegen";
$lang['mis_t'] = "Afbeelding toevoegen";
$lang['mis_u'] = "Trefwoorden<br /><small><em>gescheiden met een spatie</em></small>";
$lang['mis_v'] = "Record toevoegen anuleren";
$lang['mis_w'] = "Record ID #";
$lang['mis_x'] = "Laats bijgewerkt";
$lang['mis_y'] = "Bezocht";
$lang['mis_z'] = "Record in deze subcategorie toevoegen";
$lang['mi__1'] = "Record in deze categorie toevoegen";
$lang['mi__2'] = "Record verwijderen met NR #";
$lang['mi__3'] = "Record bewerken met NR #";
$lang['mi__4'] = "Bewerken";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Records Database";
$lang['mi__6'] = "Weergave:";
$lang['mi__7'] = "Alle records";
$lang['mi__8'] = "R NR ";
$lang['mi__9'] = "B";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Vorige pagina";
$lang['mi__b'] = "Vorige";
$lang['mi__c'] = "Records tonen van %1\$d tot %2\$d";
$lang['mi__d'] = "Volgende pagina";
$lang['mi__e'] = "Volgende";
$lang['mi__f'] = "** Er zijn geen records in deze Subcategorie **";
$lang['mi__g'] = "** Er zijn geen records in deze Categorie **";
$lang['mi__h'] = "** Er zijn geen records in deze database **";


/* ***** Error Messages *************************************** */
$lang['err1'] = "Bestands type is niet toegestaan (%s), of geen bestand om te uploaden";
$lang['err2'] = "Bestanden groter dan %1\$d BYTES (%2\$d KB) zijn niet toegestaan";
$lang['err3'] = "Bestandnaam bestaat al";
$lang['err4'] = "Upload mislukt.  de doeldirectorie moet beschrijfbaar zijn, cmod 777";
$lang['err5'] = "Ongeldige (letter)leestekens in de categorienaam";
$lang['err6'] = "Categorie bestaat al";
$lang['err7'] = "Ongeldige (letter)leestekens in de subcategorienaam";
$lang['err8'] = "Subcategorie bestaat al";
$lang['err9'] = "Geen subcategorienaam ingevoerd";
$lang['erra'] = "Record bestaat al";
$lang['errb'] = "Record NR <strong>%s</strong> bestaat niet";
$lang['errc'] = "Geen subcategorienaam ingevoerd";
$lang['errd'] = "Geen zoekterm ingevoerd";
$lang['erre'] = "Geen record ingevoerd";


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
