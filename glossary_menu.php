<?php
/**
 * Glossary by Shirka (www.shirka.org)
 *
 * A plugin for the e107 Website System (http://e107.org)
 *
 * ©Andre DUCLOS 2006
 * http://www.shirka.org
 * duclos@shirka.org
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://gnu.org).
 *
 * $Source: /home/e-smith/files/ibays/cvsroot/files/glossary/glossary_menu.php,v $
 * $Revision: 1.7 $
 * $Date: 2006/06/27 13:38:49 $
 * $Author: duclos $
 */

if (!defined('e107_INIT')) { exit; }

//--global   $rs;  ??????????

$pref = e107::getPlugConfig('glossary')->getPref();

//require_once(e_PLUGIN.'glossary/glossary_class.php');
//$gc = new glossary_class();
e107::lan('glossary','front',true);
$word_shortcodes = e107::getScBatch('glossary', 'glossary');

$endfile = (deftrue('BOOTSTRAP') === 3)?"bootstrap3":"table";
$glossarytemplate   = e107::getTemplate('glossary', 'glossary'.$endfile );
$word_shortcodes->wrapper('glossary'.$endfile.'/glossary');

//--require_once(e_HANDLER."form_handler.php");
//--$rs = new form;  ?????????

include_lan(e_PLUGIN."glossary/languages/".e_LANGUAGE."/Lan_".basename(__FILE__));

// ################## Start of class cleanup, no need for single used class methods....
//$text = $gc->displayNav("menu");  	 
$text	= $tp->parseTemplate($glossarytemplate['LINK_MENU_NAVIGATOR'], FALSE, $word_shortcodes);
// ################# End of class cleanup...

/*----
if (isset($pref['glossary_menu_lastword']) && $pref['glossary_menu_lastword'])
	$text .= $gc->buildMenuLastWord();
else
	$text .= $gc->buildMenuRandWord();
*/

// ################## Start of class cleanup, no need for single used class methods....
//	$text .= $gc->buildMenuWord(isset($pref['glossary_menu_lastword'])?"glo_datestamp DESC":"RAND()");

//	function buildMenuWord($qry)
//	{

/*
		global $sql, $rs, $ns, $tp ;
		global $glo_id, $word, $description;
*/
//    $pref = e107::getPlugConfig('glossary')->getPref();
		//require_once(e_PLUGIN.'glossary/glossary_shortcodes.php');

//    $words = $sql->retrieve("glossary", "*", "glo_approved = '1' ORDER BY ".$qry." LIMIT ".$pref['glossary_menu_number'], true);
    $words = $sql->retrieve("glossary", "*", "glo_approved = '1' ORDER BY ".(isset($pref['glossary_menu_lastword'])?"glo_datestamp DESC":"RAND()")." LIMIT ".$pref['glossary_menu_number'], true);
    if ($words)
		{
  		global $glo_id, $word, $description;
      $text .= $tp->parseTemplate($glossarytemplate['WORD_MENU_TITLE'], FALSE, $word_shortcodes);
			foreach($words as $row)
     	{
        $glo_id       = $row['glo_id'];
        $word         = $row['glo_name'];
        $description  = $row['glo_description'];

				$text .= $tp->parseTemplate($glossarytemplate['WORD_BODY_MENU'], FALSE, $word_shortcodes);
			}
		}
		else $text .= LAN_GLOSSARY_BLMENU_05;

//		return $text;
//	}
// ################# End of class cleanup...

//$caption = (isset($pref['glossary_menu_caption']) && $pref['glossary_menu_caption'] ? $pref['glossary_menu_caption'] : LAN_GLOSSARY_BLMENU_01);

//$ns->tablerender($caption, $text);
$ns->tablerender(empty($pref['glossary_menu_caption'])?LAN_GLOSSARY_BLMENU_01:$pref['glossary_menu_caption'], $text);

?>