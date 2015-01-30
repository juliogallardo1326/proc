<?php /* ***** Orca Knowledgebase - Polish Language File ****** */

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
* If you translate this file into your native language, please
* send me a copy so I can include it in the forum package.  Your
* name will appear in the header of the file you translate :)
*
* T³umaczenie  Marek Krasa ( http://www.szablony.pl )
*************************************************************** */

$lang['charset'] = "ISO-8859-2";
setlocale(LC_TIME, array("pl_PL", "pol"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-2

$sData['dateformat'] = "%b %d, %Y  %X";  // see http://www.php.net/strftime


/* ************************************************************ */
/* ***** User GUI ********************************************* */
/* ************************************************************ */

$lang['term1'] = "Szukaj";
$lang['term2'] = "Wyczy¶æ";
$lang['term3'] = "Przejd¼ do ID pytania";
$lang['term4'] = "Wszystkie kategorie";
$lang['term5'] = "Wszystkie podkategorie";
$lang['term6'] = "Dalej";
$lang['term7'] = "Zaktualizowany";
$lang['term8'] = "Kategoria";
$lang['term9'] = "Podkategoria";
$lang['terma'] = "ID pytania %s nie istnieje";
$lang['termb'] = "Powrót";
$lang['termc'] = "<strong>%d</strong> Wynik(i)";
$lang['termd'] = "Szukanie w: %s";
$lang['terme'] = "Wy¶wietlanie: %s";
$lang['termf'] = "Pytanie";
$lang['termg'] = "Nie znaleziono w bazie";
$lang['termh'] = "Poprzednie";
$lang['termi'] = "Pokazuje pytania %1\$d do %2\$d";
$lang['termj'] = "Nastêpne";
$lang['termk'] = "Poprzednia strona";
$lang['terml'] = "Nastêpna strona";
$lang['termm'] = "Nie mo¿na znale¼æ informacji? ";
$lang['termn'] = "Zadaj pytanie";
$lang['termo'] = "Twoje pytanie";
$lang['termp'] = "Twój adres e-mail";
$lang['termq'] = "Dziêkujemy!";
$lang['termr'] = "Twoje pytanie zosta³o wys³ane";
$lang['terms'] = "Rozwi±zanie";
$lang['termt'] = "Wybierz";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Przes³ane pytanie";
$lang['email1']['message'] = <<<ORCA
<%1\$s> zada³ nastêpuj±ce pytanie poprzez %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Uszereguj listê wed³ug %s";
$lang['misc2'] = "Panel kontrolny";
$lang['misc3'] = "Panel kontolny bazy wiedzy";
$lang['misc4'] = "Nie jeste¶ zalogowany";
$lang['misc5'] = "Logowanie";
$lang['misc6'] = "Jeste¶ zalogowany";
$lang['misc8'] = "Wylogowanie";
$lang['misc9'] = "Kontynuuj edycjê bazy wiedzy";
$lang['misca'] = "Powrót do trybu edycji";
$lang['miscb'] = "Zarz±dzanie uploadem plików";
$lang['miscc'] = "Upload plików";
$lang['miscd'] = "Wyst±pi³y b³êdy";
$lang['misce'] = "Poprzednia akcja zakoñczy³a siê nastêpuj±cym(i) b³êdem(ami):";
$lang['miscf'] = "Od¶wie¿ tê stronê";
$lang['miscg'] = "Wyczy¶æ b³êdy";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "Brak dostêpu do folderu, zmieñ uprawnienia dostêpu do katalogu na 777.(xrw-xrw-xrw)";
$lang['misci'] = "Od¶wie¿";
$lang['miscj'] = "Plik:";
$lang['misck'] = "Folder z plikami:";
$lang['miscl'] = "Dozwolone rodzaje plików:";
$lang['miscm'] = "Limit wielko¶ci pliku:";
$lang['miscn'] = "Upload";
$lang['misco'] = "Wyczy¶æ";
$lang['miscp'] = "Nazwa pliku";
$lang['miscq'] = "Typ pliku";
$lang['miscr'] = "Rozmiar pliku";
$lang['miscs'] = "Usuñ";
$lang['misct'] = "Usuñ plik";
$lang['miscu'] = "Jeste¶ pewien, ¿e chcesz skasowaæ ten plik?";
$lang['miscv'] = "Powrót / Od¶wie¿";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Kontrola kategorii";
$lang['miscx'] = "Dodaj kategoriê";
$lang['miscy'] = "Dodaj";
$lang['miscz'] = "Wybierz bie¿±c± kategoriê";
$lang['mis_1'] = "Wybierz kategoriê";
$lang['mis_2'] = "¯adna";
$lang['mis_3'] = "Wybierz";
$lang['mis_4'] = "Zmieñ nazwê kategorii";
$lang['mis_5'] = "Nowa nazwa";
$lang['mis_6'] = "Zmieñ nazwê";
$lang['mis_7'] = "Usuñ kategoriê";
$lang['mis_8'] = "Usuñ";
$lang['mis_9'] = "Kontrola podkategorii";
$lang['mis_a'] = "Dodaj podkategoriê";
$lang['mis_b'] = "Wybierz bie¿±c± podkategoriê";
$lang['mis_c'] = "Wybierz podkategoriê";
$lang['mis_d'] = "Zmieñ nazwê podkategorii";
$lang['mis_e'] = "Usuñ podkategoriê";
$lang['mis_f'] = "Kontrola pytañ";
$lang['mis_g'] = "Kategoria pytañ zosta³a zmieniona";
$lang['mis_h'] = "Aby zakoñczyæ edycjê, proszê wybraæ lub utworzyæ now± podkategoriê dla tego pytania";
$lang['mis_i'] = "Podkategoria";
$lang['mis_j'] = "Nowa podkategoria";
$lang['mis_k'] = "Anuluj edycjê pytania";
$lang['mis_l'] = "Anuluj";
$lang['mis_m'] = "Zakoñcz edycjê";
$lang['mis_n'] = "Kategoria";
$lang['mis_o'] = "Pytanie ma byæ wy¶wietlane na stronie";
$lang['mis_p'] = "Pytanie";
$lang['mis_q'] = "Odpowied¼<br /><small><em>Mo¿na u¿ywaæ znaczników HTML</em></small>";
$lang['mis_r'] = "Wybierz plik";
$lang['mis_s'] = "Dodaj link";
$lang['mis_t'] = "Dodaj obrazek";
$lang['mis_u'] = "S³owa kluczowe<br /><small><em>oddzielone spacj±</em></small>";
$lang['mis_v'] = "Anuluj dodwanie pytania";
$lang['mis_w'] = "ID pytania #";
$lang['mis_x'] = "Ostatnia aktualizacja";
$lang['mis_y'] = "Wy¶wietleñ";
$lang['mis_z'] = "Dodaj pytanie w tej podkategorii";
$lang['mi__1'] = "Dodaj pytanie w tej kategorii";
$lang['mi__2'] = "Usuñ pytanie z nastêpuj±cym ID #";
$lang['mi__3'] = "Edytuj pytanie z nastêpuj±cym ID #";
$lang['mi__4'] = "Edytuj";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Baza pytañ";
$lang['mi__6'] = "Wy¶wietlanie:";
$lang['mi__7'] = "Wszystkie pytania";
$lang['mi__8'] = "Q ID";
$lang['mi__9'] = "E";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Poprzednia strona";
$lang['mi__b'] = "Poprzednia";
$lang['mi__c'] = "Pokazuje pytania %1\$d do %2\$d";
$lang['mi__d'] = "Nastêpna strona";
$lang['mi__e'] = "Nastêpna";
$lang['mi__f'] = "** Nie ma pytañ w tej podkategorii **";
$lang['mi__g'] = "** Nie ma pytañ w tej kategorii **";
$lang['mi__h'] = "** Nie ma pytañ w bazie **";


/* ***** Error Messages *************************************** */
$lang['err1'] = "Ten typ pliku jest niedozwolony (%s), lub nie wybrano pliku do wgrania";
$lang['err2'] = "Plik wiêkszy ni¿ %1\$d bajtów (%2\$d KB) jest niedozwolony";
$lang['err3'] = "taki plik ju¿ istnieje";
$lang['err4'] = "Upload nie powiód³ siê.  Zmieñ uprawnienia folderu na 777";
$lang['err5'] = "Niedozwolone znaki w nazwie kategorii";
$lang['err6'] = "Kategoria ju¿ istnieje";
$lang['err7'] = "Niedozwolone znaki w nazwie podkategorii";
$lang['err8'] = "Podkategoria ju¿ istnieje";
$lang['err9'] = "Nie wprowadzono nazwy podkategorii";
$lang['erra'] = "Pytanie ju¿ istnieje";
$lang['errb'] = "ID pytania <strong>%s</strong> nie istnieje";
$lang['errc'] = "Nie wprowadzono nazwy podkategorii";
$lang['errd'] = "Nie wprowadzono pytania";
$lang['erre'] = "Nie wprowadzono odpowiedzi";


/* ***** Polish (ISO-8859-2) to UTF-8 ********
* Copyright (c) 2004 Brian Huisman AKA GreyWyvern
* PHP encoding converter from ISO-8859-2 to UTF-8
*
* Modified for PHP from the original Perl as taken from NexTrieve-0.41
* http://search.cpan.org/~elizabeth/NexTrieve-0.41/lib/NexTrieve/UTF8.pm
*
* Copyright (c) 1995-2003 Elizabeth Mattijsen <liz@dijkmat.nl>. All rights reserved.
* This program is free software; you can redistribute it and/or modify it under the same terms as Perl itself.
****************************************** */

function iso88592_2utf8($input) {
  $iso88592 = array(
   'Â€', 'Â', 'Â‚', 'Âƒ', 'Â„', 'Â…', 'Â†', 'Â‡', 'Âˆ', 'Â‰',
   'Â©', 'Â‹', 'Â¦', 'Â«', 'Â®', 'Â¬', 'Â', 'Â‘', 'Â’', 'Â“',
   'Â”', 'Â•', 'Â–', 'Â—', 'Â˜', 'Â™', 'Â±', 'Â›', 'Â¶', 'Â»',
   'Âµ', 'Â¥', 'Â ', 'Ä„', 'Ë˜', 'Å', 'Â¤', 'Ä½', 'Å±', 'Â§',
   'Â¨', 'Å ', 'Åµ', 'Å¤', 'Å±', 'Â­', 'Å½', 'Å»', 'Â°', 'Ä…',
   'Ë›', 'Å‚', 'Â´', 'Äµ', 'Å›', 'Ë‡', 'Â¸', 'Å¡', 'Å¥', 'Å¡',
   'Åº', 'Ë»', 'Åµ', 'Å¡', 'Å”', 'Ã', 'Ã‚', 'Ä‚', 'Ã„', 'Ä±',
   'Ä†', 'Ã‡', 'Ä¦', 'Ã‰', 'Ä˜', 'Ã‹', 'Ä±', 'Ã«', 'Ã®', 'Ä®',
   'Ä', 'Åƒ', 'Å‡', 'Ã“', 'Ã”', 'Å', 'Ã–', 'Ã—', 'Å˜', 'Å®',
   'Ã±', 'Å°', 'Ã¶', 'Ã»', 'Å¢', 'Ã¥', 'Å•', 'Ã¡', 'Ã¢', 'Äƒ',
   'Ã¤', 'Äº', 'Ä‡', 'Ã§', 'Ä«', 'Ã©', 'Ä™', 'Ã«', 'Ä›', 'Ã­',
   'Ã®', 'Ä¬', 'Ä‘', 'Å„', 'Åˆ', 'Ã³', 'Ã´', 'Å‘', 'Ã¶', 'Ã·',
   'Å™', 'Å¯', 'Ãº', 'Å±', 'Ã¡', 'Ã½', 'Å£', 'Ë™');

  return preg_replace("/([\x80-\xFF])/e", '$iso88592[ord($1) - 0x80]', $input);
}

if ($pageEncoding == 1) {
  while (list($key, $value) = each($lang))
    if (!is_array($value) && $key != "charset") $lang[$key] = iso88592_2utf8($value);
}

?>
