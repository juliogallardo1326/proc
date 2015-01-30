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
* T�umaczenie  Marek Krasa ( http://www.szablony.pl )
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
$lang['term2'] = "Wyczy��";
$lang['term3'] = "Przejd� do ID pytania";
$lang['term4'] = "Wszystkie kategorie";
$lang['term5'] = "Wszystkie podkategorie";
$lang['term6'] = "Dalej";
$lang['term7'] = "Zaktualizowany";
$lang['term8'] = "Kategoria";
$lang['term9'] = "Podkategoria";
$lang['terma'] = "ID pytania %s nie istnieje";
$lang['termb'] = "Powr�t";
$lang['termc'] = "<strong>%d</strong> Wynik(i)";
$lang['termd'] = "Szukanie w: %s";
$lang['terme'] = "Wy�wietlanie: %s";
$lang['termf'] = "Pytanie";
$lang['termg'] = "Nie znaleziono w bazie";
$lang['termh'] = "Poprzednie";
$lang['termi'] = "Pokazuje pytania %1\$d do %2\$d";
$lang['termj'] = "Nast�pne";
$lang['termk'] = "Poprzednia strona";
$lang['terml'] = "Nast�pna strona";
$lang['termm'] = "Nie mo�na znale�� informacji? ";
$lang['termn'] = "Zadaj pytanie";
$lang['termo'] = "Twoje pytanie";
$lang['termp'] = "Tw�j adres e-mail";
$lang['termq'] = "Dzi�kujemy!";
$lang['termr'] = "Twoje pytanie zosta�o wys�ane";
$lang['terms'] = "Rozwi�zanie";
$lang['termt'] = "Wybierz";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Przes�ane pytanie";
$lang['email1']['message'] = <<<ORCA
<%1\$s> zada� nast�puj�ce pytanie poprzez %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Uszereguj list� wed�ug %s";
$lang['misc2'] = "Panel kontrolny";
$lang['misc3'] = "Panel kontolny bazy wiedzy";
$lang['misc4'] = "Nie jeste� zalogowany";
$lang['misc5'] = "Logowanie";
$lang['misc6'] = "Jeste� zalogowany";
$lang['misc8'] = "Wylogowanie";
$lang['misc9'] = "Kontynuuj edycj� bazy wiedzy";
$lang['misca'] = "Powr�t do trybu edycji";
$lang['miscb'] = "Zarz�dzanie uploadem plik�w";
$lang['miscc'] = "Upload plik�w";
$lang['miscd'] = "Wyst�pi�y b��dy";
$lang['misce'] = "Poprzednia akcja zako�czy�a si� nast�puj�cym(i) b��dem(ami):";
$lang['miscf'] = "Od�wie� t� stron�";
$lang['miscg'] = "Wyczy�� b��dy";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "Brak dost�pu do folderu, zmie� uprawnienia dost�pu do katalogu na 777.(xrw-xrw-xrw)";
$lang['misci'] = "Od�wie�";
$lang['miscj'] = "Plik:";
$lang['misck'] = "Folder z plikami:";
$lang['miscl'] = "Dozwolone rodzaje plik�w:";
$lang['miscm'] = "Limit wielko�ci pliku:";
$lang['miscn'] = "Upload";
$lang['misco'] = "Wyczy��";
$lang['miscp'] = "Nazwa pliku";
$lang['miscq'] = "Typ pliku";
$lang['miscr'] = "Rozmiar pliku";
$lang['miscs'] = "Usu�";
$lang['misct'] = "Usu� plik";
$lang['miscu'] = "Jeste� pewien, �e chcesz skasowa� ten plik?";
$lang['miscv'] = "Powr�t / Od�wie�";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Kontrola kategorii";
$lang['miscx'] = "Dodaj kategori�";
$lang['miscy'] = "Dodaj";
$lang['miscz'] = "Wybierz bie��c� kategori�";
$lang['mis_1'] = "Wybierz kategori�";
$lang['mis_2'] = "�adna";
$lang['mis_3'] = "Wybierz";
$lang['mis_4'] = "Zmie� nazw� kategorii";
$lang['mis_5'] = "Nowa nazwa";
$lang['mis_6'] = "Zmie� nazw�";
$lang['mis_7'] = "Usu� kategori�";
$lang['mis_8'] = "Usu�";
$lang['mis_9'] = "Kontrola podkategorii";
$lang['mis_a'] = "Dodaj podkategori�";
$lang['mis_b'] = "Wybierz bie��c� podkategori�";
$lang['mis_c'] = "Wybierz podkategori�";
$lang['mis_d'] = "Zmie� nazw� podkategorii";
$lang['mis_e'] = "Usu� podkategori�";
$lang['mis_f'] = "Kontrola pyta�";
$lang['mis_g'] = "Kategoria pyta� zosta�a zmieniona";
$lang['mis_h'] = "Aby zako�czy� edycj�, prosz� wybra� lub utworzy� now� podkategori� dla tego pytania";
$lang['mis_i'] = "Podkategoria";
$lang['mis_j'] = "Nowa podkategoria";
$lang['mis_k'] = "Anuluj edycj� pytania";
$lang['mis_l'] = "Anuluj";
$lang['mis_m'] = "Zako�cz edycj�";
$lang['mis_n'] = "Kategoria";
$lang['mis_o'] = "Pytanie ma by� wy�wietlane na stronie";
$lang['mis_p'] = "Pytanie";
$lang['mis_q'] = "Odpowied�<br /><small><em>Mo�na u�ywa� znacznik�w HTML</em></small>";
$lang['mis_r'] = "Wybierz plik";
$lang['mis_s'] = "Dodaj link";
$lang['mis_t'] = "Dodaj obrazek";
$lang['mis_u'] = "S�owa kluczowe<br /><small><em>oddzielone spacj�</em></small>";
$lang['mis_v'] = "Anuluj dodwanie pytania";
$lang['mis_w'] = "ID pytania #";
$lang['mis_x'] = "Ostatnia aktualizacja";
$lang['mis_y'] = "Wy�wietle�";
$lang['mis_z'] = "Dodaj pytanie w tej podkategorii";
$lang['mi__1'] = "Dodaj pytanie w tej kategorii";
$lang['mi__2'] = "Usu� pytanie z nast�puj�cym ID #";
$lang['mi__3'] = "Edytuj pytanie z nast�puj�cym ID #";
$lang['mi__4'] = "Edytuj";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Baza pyta�";
$lang['mi__6'] = "Wy�wietlanie:";
$lang['mi__7'] = "Wszystkie pytania";
$lang['mi__8'] = "Q ID";
$lang['mi__9'] = "E";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Poprzednia strona";
$lang['mi__b'] = "Poprzednia";
$lang['mi__c'] = "Pokazuje pytania %1\$d do %2\$d";
$lang['mi__d'] = "Nast�pna strona";
$lang['mi__e'] = "Nast�pna";
$lang['mi__f'] = "** Nie ma pyta� w tej podkategorii **";
$lang['mi__g'] = "** Nie ma pyta� w tej kategorii **";
$lang['mi__h'] = "** Nie ma pyta� w bazie **";


/* ***** Error Messages *************************************** */
$lang['err1'] = "Ten typ pliku jest niedozwolony (%s), lub nie wybrano pliku do wgrania";
$lang['err2'] = "Plik wi�kszy ni� %1\$d bajt�w (%2\$d KB) jest niedozwolony";
$lang['err3'] = "taki plik ju� istnieje";
$lang['err4'] = "Upload nie powi�d� si�.  Zmie� uprawnienia folderu na 777";
$lang['err5'] = "Niedozwolone znaki w nazwie kategorii";
$lang['err6'] = "Kategoria ju� istnieje";
$lang['err7'] = "Niedozwolone znaki w nazwie podkategorii";
$lang['err8'] = "Podkategoria ju� istnieje";
$lang['err9'] = "Nie wprowadzono nazwy podkategorii";
$lang['erra'] = "Pytanie ju� istnieje";
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
   '', '', '', '', '', '', '', '', '', '',
   '©', '', '¦', '«', '®', '¬', '', '', '', '',
   '', '', '', '', '', '', '±', '', '¶', '»',
   'µ', '¥', ' ', 'Ą', '˘', 'Ł', '¤', 'Ľ', 'ű', '§',
   '¨', 'Š', 'ŵ', 'Ť', 'ű', '­', 'Ž', 'Ż', '°', 'ą',
   '˛', 'ł', '´', 'ĵ', 'ś', 'ˇ', '¸', 'š', 'ť', 'š',
   'ź', '˻', 'ŵ', 'š', 'Ŕ', 'Á', 'Â', 'Ă', 'Ä', 'ı',
   'Ć', 'Ç', 'Ħ', 'É', 'Ę', 'Ë', 'ı', 'ë', 'î', 'Į',
   'Đ', 'Ń', 'Ň', 'Ó', 'Ô', 'Ő', 'Ö', '×', 'Ř', 'Ů',
   'ñ', 'Ű', 'ö', 'û', 'Ţ', 'å', 'ŕ', 'á', 'â', 'ă',
   'ä', 'ĺ', 'ć', 'ç', 'ī', 'é', 'ę', 'ë', 'ě', 'í',
   'î', 'Ĭ', 'đ', 'ń', 'ň', 'ó', 'ô', 'ő', 'ö', '÷',
   'ř', 'ů', 'ú', 'ű', 'á', 'ý', 'ţ', '˙');

  return preg_replace("/([\x80-\xFF])/e", '$iso88592[ord($1) - 0x80]', $input);
}

if ($pageEncoding == 1) {
  while (list($key, $value) = each($lang))
    if (!is_array($value) && $key != "charset") $lang[$key] = iso88592_2utf8($value);
}

?>
