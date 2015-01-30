<?php /* ***** Orca Knowledgebase - French Language File ***** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  Un syst�me de gestion de questions/r�ponses, petit mais efficace.
* Copyright (C) 2004 GreyWyvern
*
* Peut �tre distribu� selon les termes de la license GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* Instructions pour son installation dans "readme.txt".
*
* Traduction par Andy Funnell ( http://www.funnell.org/ )
*  - Ajust� par Tian 2.0a ( http://www.c-sait.net/ )
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("fr_FR", "fra"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$sData['dateformat'] = "%b %d, %Y  %X";  // see http://www.php.net/strftime


/* ************************************************************ */
/* ***** User GUI ********************************************* */
/* ************************************************************ */

$lang['term1'] = "Chercher";
$lang['term2'] = "Effacer";
$lang['term3'] = "Aller � cette question";
$lang['term4'] = "Toutes les rubriques";
$lang['term5'] = "Toutes les sous-rubriques";
$lang['term6'] = "Aller";
$lang['term7'] = "Mis � jour";
$lang['term8'] = "Cat�gorie";
$lang['term9'] = "Sous-cat�gorie";
$lang['terma'] = "Question n� %s n'existe pas";
$lang['termb'] = "Retour";
$lang['termc'] = "<strong>%d</strong> Resultat(s)";
$lang['termd'] = "Recherche dans : %s";
$lang['terme'] = "Affichage de : %s";
$lang['termf'] = "Question";
$lang['termg'] = "Aucun r�sultat trouv�";
$lang['termh'] = "Pr�c�dent";
$lang['termi'] = "Affichage des questions %1\$d � %2\$d";
$lang['termj'] = "Suivant";
$lang['termk'] = "Page pr�c�dente";
$lang['terml'] = "Page suivante";
$lang['termm'] = "Vous n'avez pas trouv� votre r�ponse ?";
$lang['termn'] = "Envoyer une question � l'administrateur ?";
$lang['termo'] = "Votre question";
$lang['termp'] = "Votre adresse e-mail";
$lang['termq'] = "Merci !";
$lang['termr'] = "Votre question a �t� envoy�e";
$lang['terms'] = "Solution";
$lang['termt'] = "Filtre";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Question utilisateur";
$lang['email1']['message'] = <<<ORCA
<%1\$s> a pos� la question suivante par %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Trier la liste par %s";
$lang['misc2'] = " - Panneau de contr�le";
$lang['misc3'] = "Panneau de contr�le de la base de connaissances";
$lang['misc4'] = "Vous n'�tes pas connect�";
$lang['misc5'] = "Se connecter";
$lang['misc6'] = "Vous �tes connect�";
$lang['misc8'] = "Se d�connecter";
$lang['misc9'] = "Continuer � �diter la base de connaissances";
$lang['misca'] = "Retourner en mode �dition";
$lang['miscb'] = "Gestionnaire de t�l�chargements";
$lang['miscc'] = "T�l�charger des fichiers compl�mentaires";
$lang['miscd'] = "Il y a eu des erreurs";
$lang['misce'] = "L'action pr�c�dente a g�n�r� les erreurs suivantes:";
$lang['miscf'] = "Rafra�chir la page";
$lang['miscg'] = "Effacer l'erreur";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "Impossible d'acc�der au r�pertoire des fichiers. veuillez changer les droits d'acc�s de %s avec un chmod 777 (xrw-xrw-xrw)";
$lang['misci'] = "Rafra�chir";
$lang['miscj'] = "Fichier :";
$lang['misck'] = "R�pertoire de stockage :";
$lang['miscl'] = "Types de fichiers permis :";
$lang['miscm'] = "Taille maximale de fichier :";
$lang['miscn'] = "T�l�charger";
$lang['misco'] = "Effacer";
$lang['miscp'] = "Nom de fichier";
$lang['miscq'] = "Type de fichier";
$lang['miscr'] = "Taille de fichier";
$lang['miscs'] = "Supprimer";
$lang['misct'] = "Supprimer le fichier";
$lang['miscu'] = "Etes-vous s�r de vouloir supprimer ce fichier ?";
$lang['miscv'] = "Retour / Rafra�chir";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Gestion des rubriques";
$lang['miscx'] = "Ajouter une rubrique";
$lang['miscy'] = "Ajouter";
$lang['miscz'] = "Selectionner la rubrique de travail";
$lang['mis_1'] = "Selectionner une rubrique";
$lang['mis_2'] = "Aucun";
$lang['mis_3'] = "Selectionner";
$lang['mis_4'] = "Renommer une rubrique";
$lang['mis_5'] = "Nouveau nom";
$lang['mis_6'] = "Renommer";
$lang['mis_7'] = "Supprimer une rubrique";
$lang['mis_8'] = "Supprimer";
$lang['mis_9'] = "Gestion des sous-rubriques";
$lang['mis_a'] = "Ajouter une sous-rubrique";
$lang['mis_b'] = "Selectionner une sous-rubrique de travail";
$lang['mis_c'] = "Selectionner une sous-rubrique";
$lang['mis_d'] = "Renommer la sous-rubrique";
$lang['mis_e'] = "Supprimer une sous-rubrique";
$lang['mis_f'] = "Gestion des questions";
$lang['mis_g'] = "Vous avez chang� cette question de rubrique";
$lang['mis_h'] = "Pour terminer cette �dition, veuillez s�lectionner une nouvelle sous-rubrique";
$lang['mis_i'] = "Sous-rubrique";
$lang['mis_j'] = "Nouvelle sous-rubrique";
$lang['mis_k'] = "Abandonner l'�dition des questions";
$lang['mis_l'] = "Abandonner";
$lang['mis_m'] = "Terminer l'�dition";
$lang['mis_n'] = "Rubrique";
$lang['mis_o'] = "En ligne";
$lang['mis_p'] = "Question";
$lang['mis_q'] = "R�ponse<br /><small><em>HTML autoris�</em></small>";
$lang['mis_r'] = "S�lectionner un fichier";
$lang['mis_s'] = "Ajouter un lien";
$lang['mis_t'] = "Ajouter une image";
$lang['mis_u'] = "Mots clefs<br /><small><em>s�par�s par des espaces</em></small>";
$lang['mis_v'] = "Abandonner l'ajout de question";
$lang['mis_w'] = "Question n�";
$lang['mis_x'] = "Derni�re mise � jour";
$lang['mis_y'] = "Lectures";
$lang['mis_z'] = "Ajouter une question dans cette sous-rubrique";
$lang['mi__1'] = "Ajouter une question dans cette rubrique";
$lang['mi__2'] = "Effacer une question par son n�";
$lang['mi__3'] = "Editer une question par son n�";
$lang['mi__4'] = "Editer";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Base de donn�es des questions";
$lang['mi__6'] = "Affichage de :";
$lang['mi__7'] = "Toutes les questions";
$lang['mi__8'] = "Q n�";
$lang['mi__9'] = "E";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Page pr�c�dente";
$lang['mi__b'] = "Pr�c�dent";
$lang['mi__c'] = "Affichage des questions %1\$d � %2\$d";
$lang['mi__d'] = "Page suivante";
$lang['mi__e'] = "Suivant";
$lang['mi__f'] = "** Il n'y a pas de questions dans cette sous-rubrique **";
$lang['mi__g'] = "** Il n'y a pas de questions dans cette rubrique **";
$lang['mi__h'] = "** Il n'y a pas de questions dans la base de donn�es **";


/* ***** Error Messages *************************************** */
$lang['err1'] = "Type de fichier interdit (%s), ou aucun fichier � t�l�charger";
$lang['err2'] = "Fichiers d�passant %1\$d octets (%2\$d KB) interdits";
$lang['err3'] = "Ce nom de fichier existe d�j�";
$lang['err4'] = "T�l�chargement �chou�.  Faites un chmod 777 sur le r�pertoire cible";
$lang['err5'] = "Caract�res non-conformes dans le nom de rubrique";
$lang['err6'] = "La rubrique existe d�j�";
$lang['err7'] = "Caract�res non-conformes dans le nom de sous-rubrique";
$lang['err8'] = "La sous-rubrique existe d�j�";
$lang['err9'] = "Nom de sous-rubrique manquant";
$lang['erra'] = "La question existe d�j�";
$lang['errb'] = "La question n� <strong>%s</strong> n'existe pas";
$lang['errc'] = "Aucun nom de sous-rubrique sp�cifi�";
$lang['errd'] = "Aucune question saisie";
$lang['erre'] = "Aucune r�ponse saisie";


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